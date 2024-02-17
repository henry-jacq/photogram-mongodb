<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('GET')) {
        if ($this->paramsExists(['page'])) {
            $page = $this->data['page'];

            // Handle inputs
            // Input should start with 1 and the flow go
            // Not contains strings
            // only integer
            
            // Load second batch of posts
            if($page > 0) {
                $page += 1;
            }
            
            // Number of posts per page
            $limit = 10;
            
            // Iteration to skip
            $skip = ($page - 1) * $limit;

            $posts = $this->post->fetchPosts($limit, $skip);

            if(empty($posts)) {
                $this->response([
                    'message' => 'Not Found'
                ], 301);
            }

            ob_start();

            foreach($posts as $post) {
                $data = ['_id' => $this->post->createMongoId($post['user_id'])];
                $user = $this->user->getUserDetails($data);
                $avatar = $this->user->getUserAvatar($user);

                echo("<div class='new-item grid-item col-xxl-3 col-lg-4 col-md-6' id='post-{$post['_id']}'>");
                
                $this->view->renderComponent('card', [
                    'p' => $post,
                    'user' => $user,
                    'avatar' => $avatar
                ]);

                echo ("</div>");
            }

            $contents = ob_get_clean();

            if (ob_get_length() > 0) {
                ob_end_clean();
            }

            $response = $this->response(
                ['html' => $contents],
                contentType: 'text/html'
            );

            return $response;
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
