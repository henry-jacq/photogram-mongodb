<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['pid', 'comment']) && $this->isMethod('POST')) {
        $pid = $this->data['pid'];
        $uid = $this->getUserId();
        $user = $this->user->getUser();
        $avatar = $this->user->getUserAvatar($user);
        $text = $this->data['comment'];

        $result = $this->post->addComment($pid, $uid, $text);
        
        if ($result !== false) {
            return $this->response([
                'message' => true,
                'username' => $user['username'],
                'fullname' => $user['fullname'],
                'avatar' => $avatar,
                'comment_id' => $result
            ], 200);
        }

        return $this->response([
            'message' => false
        ], 401);

    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
