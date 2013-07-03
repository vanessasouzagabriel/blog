<?php

	class PostsController extends AppController {
   		public $helpers = array ('Html','Form');
    	public $name = 'Posts';

    	function index() {
        	$this->set('posts', $this->Post->find('all'));
    	}

    	public function view($id = null) {
        	$this->Post->id = $id;
        	$this->set('post', $this->Post->read());
    	}

    	function edit($id = null) {
    		$this->Post->id = $id;
    			if ($this->request->is('get')) {
        			$this->request->data = $this->Post->read();
    			} else {
        		if ($this->Post->save($this->request->data)) {
            		$this->Session->setFlash('Your post has been updated.');
            		$this->redirect(array('action' => 'index'));
        		}
    		}
		}
		


		function delete($id) {
    		if (!$this->request->is('post')) {
        		throw new MethodNotAllowedException();
    		}
    		if ($this->Post->delete($id)) {
        		$this->Session->setFlash('The post with id: ' . $id . ' has been deleted.');
        		$this->redirect(array('action' => 'index'));
    		}
		}




    	public function add() {
            if ($this->request->is('post')) {
                $this->request->data['Post']['user_id'] = $this->Auth->user('id'); //Added this line
                if ($this->Post->save($this->request->data)) {
                    $this->Session->setFlash('Your post has been saved.');
                    $this->redirect(array('action' => 'index'));
                }
            }
        }

        public function isAuthorized($user) {
            if (!parent::isAuthorized($user)) {
                if ($this->action === 'add') {
                    // All registered users can add posts
                    return true;
                }
                if (in_array($this->action, array('edit', 'delete'))) {
                    $postId = $this->request->params['pass'][0];
                    return $this->Post->isOwnedBy($postId, $user['id']);
                }
            }
            return false;
        }

	}


?>