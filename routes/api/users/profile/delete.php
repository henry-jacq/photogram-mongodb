<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {

        $this->user->deleteAvatar(
            $this->getUserId()
        );

        return $this->response([
            'message' => true
        ], 200);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
