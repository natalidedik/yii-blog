<?php

class PostController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('deny', // deny user to perform actions
                'actions' => array('create', 'update', 'delete'),
                'roles' => array('user'),
            ),
            array('allow', // allow admin to perform all actions
                'actions' => array('create', 'update', 'delete', 'index', 'view'),
                'roles' => array('admin'),
            ),
            array('allow', // allow moderator to perform update
                'actions' => array('update'),
                'roles' => array('moderator'),
            ),
            array('deny', // deny moderator to perform create
                'actions' => array('create'),
                'roles' => array('moderator'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView() {
        $post = $this->loadModel();
        $comment = $this->newComment($post);

        $this->render('view', array(
            'model' => $post,
            'comment' => $comment,
        ));
    }

    /* создаем экземпляр класса Comment и проверяем, отправлена ли форма комментария */

    protected function newComment($post) {
        $comment = new Comment;
        /* часть отвечающая на запросы AJAX валидации. 
         * Код проверяет, есть ли параметр POST с именем ajax. 
         * Если есть — отдает результат валидации, используя CActiveForm::validate. */
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'comment-form') {
            echo CActiveForm::validate($comment);
            Yii::app()->end();
        }


        if (isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];
            /* пробуем добавить комментарий к записи */
            if ($post->addComment($comment)) {
                if ($comment->status == Comment::STATUS_PENDING)
                    Yii::app()->user->setFlash('commentSubmitted', 'Thank you for your comment.
                        Your comment will be posted once it is approved.');
                $this->refresh();
            }
        }
        return $comment;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Post;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel()->delete();

            if (!isset($_GET['ajax']))
                $this->redirect(array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        /* создается критерий запроса для получения списка записей
         * Критерий включает ограничения на получение только опубликованных записей 
         * и сортировку по времени их обновления в обратном порядке. 
         * Так как при отображении записи в списке мы также хотим показывать 
         * количество комментариев, в критерии указывается необходимость 
         * получения связи commentCount, описанного в Post::relations(). */
        $criteria = new CDbCriteria(array(
            'condition' => 'status=' . Post::STATUS_PUBLISHED,
            'order' => 'create_time DESC',
            'with' => 'commentCount',
        ));
        /* В том случае, когда пользователь хочет получить записи с определённым тегом, 
         * мы добавляем в критерий условие поиска тега. */
        if (isset($_GET['tag']))
            $criteria->addSearchCondition('tags', $_GET['tag']);

        /* Используя критерий мы создаём провайдер данных, нужный для: 1) занимается постраничной 
         * разбивкой данных. Мы задаём количество результатов на страницу равным 5. 
         * 2) данные сортируются в соответствии с запросом пользователя. 
         * 3) провайдер отдаёт разбитые на страницы отсортированные данные виджетам или отображению. */
        $dataProvider = new CActiveDataProvider('Post', array(
            'pagination' => array(
                'pageSize' => 5,
            ),
            'criteria' => $criteria,
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        /* Cоздаётся модель Post со сценарием search, которую будем использовать 
         * для сбора критериев поиска, указанных пользователем. 
         */
        $model = new Post('search');
        /* присваиваем данные, введённые пользователем, модели */
        if (isset($_GET['Post']))
            $model->attributes = $_GET['Post'];
        /* выводим отображение admin, используя модель */
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Post the loaded model
     * @throws CHttpException
     */
    private $_model;

    public function loadModel() {
        /* получаем запись из таблицы Post, используя параметр id из GET. 
         * Если запись не найдена, не опубликована или находится в архиве, 
         * и при этом пользователь является гостем — показывается ошибка 404. 
         * Иначе возвращаем объект записи методу actionView(), 
         * который передаёт объект отображению. */
        if ($this->_model === null) {
            if (isset($_GET['id'])) {
                if (Yii::app()->user->isGuest) {
                    $condition = 'status=' . Post::STATUS_PUBLISHED
                            . ' OR status=' . Post::STATUS_ARCHIVED;
                } else {
                    $condition = '';
                }
                $this->_model = Post::model()->findByPk($_GET['id'], $condition);
            }
            if ($this->_model === null)
                throw new CHttpException(404, 'Запрашиваемая страница не существует.');
        }
        return $this->_model;
    }

    /**
     * Performs the AJAX validation.
     * @param Post $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'post-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

//    public $auth;
//
//    protected function loadAuth() {
//        $auth = new CPhpAuthManager;
//
//        //we are creating a default role sothat every one including anonymous will get the role.
//        $auth->defaultRoles = array('global');
//
//        //we are creating items in the name of every menu item.
//        $auth->createOperation('home');
//        $auth->createOperation('about');
//        $auth->createOperation('contact');
//        $auth->createOperation('posts');
//        $auth->createOperation('postsAdmin');
//        $auth->createOperation('login', 'login', 'return Yii::app()->user->isGuest;'); //declaring a business rule.
//        $auth->createOperation('logout');
//
//        //creating default role 'global'                
//        $global = $auth->createRole('global');
//        $global->addChild('home');
//        $global->addChild('about');
//        $global->addChild('login');
//
//        //creating role 'registered' and adding child 'global'          
//        $reg = $auth->createRole('registered');
//        $reg->addChild('contact');
//        $reg->addChild('global');
//        $reg->addChild('posts');
//        $reg->addChild('logout');
//
//        //creating role 'admin' and adding children 'global' and 'registered'           
//        $admin = $auth->createRole('admin');
//        $admin->addChild('registered');
//        $admin->addChild('postsAdmin');
//
//        //assigning roles       
//        if (!Yii::app()->user->isGuest)
//            $auth->assign(Yii::app()->user->role, Yii::app()->user->id);
//        return $this->auth = $auth;
//    }
//
//    public function init() {
//        $this->loadAuth();
//    }

}
