<?php

namespace humhub\modules\geolocation\controllers;

use humhub\modules\geolocation\models\SearchForm;
use humhub\components\Controller;
use humhub\modules\directory\components\UserPostsStreamAction;
use humhub\modules\directory\widgets\GroupStatistics;
use humhub\modules\directory\widgets\MemberStatistics;
use humhub\modules\directory\widgets\NewMembers;
use humhub\modules\directory\widgets\NewSpaces;
use humhub\modules\directory\widgets\SpaceStatistics;
use humhub\modules\directory\widgets\Sidebar;
use humhub\modules\search\libs\SearchResultSet;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\Group;
use humhub\modules\geolocation\models\User;
use Yii;
use yii\base\Event;
use yii\data\Pagination;

class IndexController extends Controller
{

    public $subLayout = '_layout';
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->subLayout = '_layout';
        $this->setActionTitles([
            'members' => Yii::t('DirectoryModule.base', 'Members'),
            'spaces' => Yii::t('AdminModule.base', 'Spaces'),
            'user-posts' => Yii::t('AdminModule.base', 'User posts'),
        ]);

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'stream' => [
                'class' => UserPostsStreamAction::class,
                'mode' => UserPostsStreamAction::MODE_NORMAL,
            ],
        ];
    }

    /**
     * Index Action, redirects to member actions
     */
    public function actionIndex()
    {
        if ($this->module->isGroupListingEnabled()) {
            return $this->redirect(['groups']);
        } else {
            return $this->redirect(['members']);
        }
    }

    /**
     * Action for the members section of the directory
     *
     * @todo Dont pass lucene hits to view, build user array inside of action
     */
    public function actionMembers()
    {
        $model = new SearchForm();

        $group = null;
        $pagination = new Pagination([]);
        if ($model->load(Yii::$app->request->post())) {


            if ($model->groupID) {
                $group = Group::findOne(['id' => $model->groupID, 'show_at_directory' => 1]);
            }

            $searchOptions = [
                'model' => \humhub\modules\user\models\User::class,
                'page' => $model->page,
                'pageSize' => $this->module->pageSize,
            ];

            if ($this->module->memberListSortField != '') {
                $searchOptions['sortField'] = $this->module->memberListSortField;
            }

            if ($group !== null) {
                $searchOptions['filters'] = ['groups' => $group->id];
            }


        }

        if ($model->keyword != '') {
            $searchResultSet = Yii::$app->search->find($model->keyword, $searchOptions);

            $pagination = new Pagination([
                'totalCount' => $searchResultSet->total,
                'pageSize' => $searchResultSet->pageSize
            ]);

        } else {

            $limit = $this->module->pageSize;

            if ($model->page > 1) {
                $limit = ($model->page * $this->module->pageSize) . ', ' . $this->module->pageSize;
            }

            if ($model->latitude == null || $model->longitude == null) {
                $user = User::find()->where(['id' => Yii::$app->user->id])->one();
                /* @var $user User */
                $model->latitude = $user->latitude;
                $model->longitude = $user->longitude;
            }

            /* @var $searchResultSet User[] */
            if ($model->latitude != null && $model->longitude != null) {

                if ($model->distance == null) {
                    $model->distance = 25;
                }

                $commandString = 'SELECT *, get_distance_in_miles_between_geo_locations( ' . $model->latitude . ', ' . $model->longitude . ', latitude, longitude) as distance
                    from user WHERE longitude BETWEEN ' . $model->longitude . ' - ' . $model->distance . ' /abs(cos(radians(' . $model->latitude . '))*69) and ' . $model->longitude . ' + ' . $model->distance . ' /abs(cos(radians(' . $model->latitude . '))*69)
                    and latitude BETWEEN ' . $model->latitude . ' - (' . $model->distance . '/69) and ' . $model->latitude . '+(' . $model->distance . '/69) and id != ' . Yii::$app->user->id;

                if ($model->page > 1) {
                    $limit = ($model->page * $this->module->pageSize) . ', ' . $this->module->pageSize;
                } else {
                    $limit = $this->module->pageSize;
                }

                $commandString .= ' ORDER BY distance limit ' . $limit;

                $searchResultSet = Yii::$app->db->createCommand($commandString)->queryAll();

                $instances = [];
                foreach ($searchResultSet as $result) {
                    $modelClass = User::class;
                    $instance = $modelClass::findOne(['id' => $result['id']]);
                    $instance->distance = $result['distance'];
                    array_push($instances, $instance);
                }
                $searchResultSet = $instances;

                if (count($searchResultSet) < $this->module->pageSize) {

                    $users = User::find()->where(['latitude' => null])->limit($this->module->pageSize - count($searchResultSet))->all();
                    $instances = [];
                    foreach ($users as $user) {
                        $modelClass = User::class;
                        $instance = $modelClass::findOne(['id' => $user->id]);
                        $instance->distance = "Unknown Distance";
                        array_push($instances, $instance);
                    }

                    $searchResultSet = array_merge($searchResultSet, $instances);
                }

                $pagination = new Pagination([
                    'totalCount' => count($searchResultSet),
                    'pageSize' => $this->module->pageSize
                ]);
            } else {
                $pagination = new Pagination([]);
            }

        }

        Event::on(Sidebar::class, Sidebar::EVENT_INIT, function($event) {
            $event->sender->addWidget(NewMembers::class, [], ['sortOrder' => 10]);
            $event->sender->addWidget(MemberStatistics::class, [], ['sortOrder' => 20]);
        });

        $users = null;
        if ($model->keyword == null) {
            if ($model->latitude != null && $model->longitude != null) {

                $users = $searchResultSet;
            } else {
                $users = new SearchResultSet();
                $users = $users->getResultInstances();
            }

        } else {
            $users = $searchResultSet->getResultInstances();
        }

        if (yii::$app->request->isAjax) {
            return $this->render('members', [
                'model' => $model,
                'group' => $group,
                'users' => $users,
                'pagination' => $pagination
            ]);
        } else {
            return $this->render('members', [
                'model' => $model,
                'group' => $group,
                'users' => $users,
                'pagination' => $pagination
            ]);
        }

    }

    public function actionUpdatelocation($lat, $long) {

        $record = User::find()->where(['id' => Yii::$app->user->id])->one();
        $record->latitude = $lat;
        $record->longitude = $long;
        if ($record->save()) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Space Section of directory
     *
     * Provides a list of all visible spaces.
     *
     * @todo Dont pass lucene hits to view, build user array inside of action
     */
    public function actionSpaces()
    {
        $keyword = Yii::$app->request->get('keyword', '');
        $page = (int) Yii::$app->request->get('page', 1);

        $searchResultSet = Yii::$app->search->find($keyword, [
            'model' => Space::class,
            'page' => $page,
            'sortField' => ($keyword == '') ? 'title' : null,
            'pageSize' => $this->module->pageSize,
        ]);

        $pagination = new Pagination([
            'totalCount' => $searchResultSet->total,
            'pageSize' => $searchResultSet->pageSize
        ]);

        Event::on(Sidebar::class, Sidebar::EVENT_INIT, function($event) {
            $event->sender->addWidget(NewSpaces::class, [], ['sortOrder' => 10]);
            $event->sender->addWidget(SpaceStatistics::class, [], ['sortOrder' => 20]);
        });

        return $this->render('spaces', [
            'keyword' => $keyword,
            'spaces' => $searchResultSet->getResultInstances(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * Group Section of the directory
     *
     * Shows a list of all groups in the application.
     */
    public function actionGroups()
    {
        if (!$this->module->isGroupListingEnabled()) {
            return $this->redirect(['members']);
        }

        $groups = Group::getDirectoryGroups();

        Event::on(Sidebar::class, Sidebar::EVENT_INIT, function($event) {
            $event->sender->addWidget(GroupStatistics::class, [], ['sortOrder' => 10]);
        });

        return $this->render('groups', [
            'groups' => $groups,
        ]);
    }

    /**
     * User Posts
     *
     * Shows public all user posts inside a wall.
     *
     * @todo Add some statistics to the view
     */
    public function actionUserPosts()
    {
        return $this->render('userPosts', []);
    }

}

