<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        if ($this->paramsExists(['id'])) {
            $pid = $this->data['id'];
            $uid = $this->getUserId();
            $result = $this->post->toggleLikes($pid, $uid);

            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
