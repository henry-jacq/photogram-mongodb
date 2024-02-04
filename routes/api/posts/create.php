<?php

${basename(__FILE__, '.php')} = function () {  
    if (isset($this->files['file'][0]) && !empty($this->files['file'][0])) {
        $files = $this->files['file'];
        
        $paths = [];
        if (count($files) > 1) {
            foreach ($files as $file) {
                $paths[] = $file->getFilePath();
            }
        } else {
            $paths[] = $files[0]->getFilePath();
        }

        $data = [
            'images' => $paths,
            'user_id' => $this->getUserId(),
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
