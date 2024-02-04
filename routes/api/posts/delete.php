<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        if ($this->paramsExists(['id'])) {
            
            $pid = $this->data['id'];
            $pdata = $this->post->getPostById($pid);

            if ($pdata['user_id'] !== $this->getUserId()) {
                return $this->response([
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            $result = $this->post->deletePost($pid);
            
            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
