<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['post_id', 'comment_id']) && $this->isMethod('POST')) {

        $pid = $this->data['post_id'];
        $cid = $this->data['comment_id'];

        $result = $this->post->deleteComment($pid, $cid);

        return $this->response([
            'message' => $result
        ], $result ? 200 : 401);

    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
