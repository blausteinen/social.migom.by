<?php

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-19 at 18:28:02.
 */
class CommentsControllerTest extends CTestCase {

    function setUp() {
        parent::setUp();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * Api comments get List and add
     * @covers CommentsController::actionPostEntity
     * @covers CommentsController::actionGetEntityList
     * @covers CommentsApi
     * @todo Сделать тестирование лимита и старта
     */
    public function testActionAuth() {
        $model  = new CommentsApi();
        
        $entity = 'news';
        $entity_id = 13;
        $text = 'test text';
        $parent_id = 0;
        $user = 1;
//        $limit = null;
//        $start = null;
        
        
        $params = array(
            'entity_id' => $entity_id,
            'text' => $text,
            'parent_id' => $parent_id,
            'user_id' => $user,
        );
        
        $getParams['id'] = $entity_id;
//        if ($limit) {
//            $getParams['limit'] = (int)$_GET['limit'];
//        }
//        if ($start) {
//            $getParams['start'] = $_GET['start'];
//        }
        
        $comments = $model->getEntityList($entity, $getParams);
        
        $this->assertTrue(isset($comments->content->count));
        $count = $comments->content->count++;
        
        $responce = $model->postEntity($entity, $params);
        $this->assertTrue($responce->content->success);
        
        $comments = $model->getEntityList($entity, $getParams);
        $this->assertEquals($count, $comments->content->count);
        
    }
   

}