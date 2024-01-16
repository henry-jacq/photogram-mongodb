<?php

${basename(__FILE__, '.php')} = function () {  

    if (isset($this->files['file']) && !empty($this->files['file'])) {
        $data = [
            'user_id' => $_SESSION['user'],
            'images' => $this->files['file'],
            'text' => $this->data['post_text'],
        ];
        
        if ($this->post->createPost($data)) {
            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'message' => true
            ], 200);
        }
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Not Created'
        ], 402);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
