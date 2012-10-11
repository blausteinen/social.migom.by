<?php

class CommentsController extends Controller
{
    public function actionIndex(){
        $this->render('index');
    }
    
    public function actionList($model){
        $modelTitle = $model;
        $model= Comments::model($modelTitle);
		$model->unsetAttributes();
        if(isset($_GET['NewsComments'])){
            $model->attributes = $_GET['NewsComments'];
        }
        
        $this->render('list', array('model' => $model, 'modelTitle' => $modelTitle));
    }
    
    public function actionTree($model, $id){
        $modelTitle = $model;
        $model= Comments::model($modelTitle);
        $model = $model->findByPk($id);
        if($model->status == Comments::STATUS_UNMODERATED){
            $model->status = Comments::STATUS_UNPUBLISHED;
            $model->moderate_id = Yii::app()->user->id;
            $model->save();
        }
        
        $this->renderPartial('tree', array('model' => $model, 'modelTitle' => $modelTitle));
    }
    
    public function actionApproove($model, $id){
        $modelTitle = $model;
        $model= Comments::model($modelTitle);
        $model = $model->findByPk($id);
        $model->status = Comments::STATUS_PUBLISHED;
        $model->attributes = $_POST[get_class($model)];
        $model->moderate_id = Yii::app()->user->id;
        $model->save();
    }
    
    public function actionSave($model, $id){
        $modelTitle = $model;
        $model= Comments::model($modelTitle);
        $model = $model->findByPk($id);
        $model->attributes = $_POST[get_class($model)];
        $model->moderate_id = Yii::app()->user->id;
        $model->save();
    }
    
    public function actionDelete($model, $id){
        $modelTitle = $model;
        $model= Comments::model($modelTitle);
        $model = $model->findByPk($id);
        $model->status = Comments::STATUS_DELETED;
        $model->moderate_id = Yii::app()->user->id;
        $model->save();
    }
}
