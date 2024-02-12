<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['pid', 'comment']) && $this->isMethod('POST')) {
        $pid = $this->data['pid'];
        $uid = $this->getUserId();
        $text = $this->data['comment'];

        $result = $this->post->addComment($pid, $uid, $text);
        
        return $this->response([
            'message' => $result
        ], $result ? 200 : 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
