<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        if ($this->paramsExists(['id', 'text'])) {
            
            $pid = $this->data['id'];
            $pdata = $this->post->getPostById($pid);

            if ($pdata['user_id'] !== $this->getUserId()) {
                return $this->response([
                    'message' => 'Not Modified'
                ], 304);
            }
            
            $text = htmlspecialchars($this->data['text']);
            
            $result = $this->post->updatePostText($pid, $text);
            
            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
