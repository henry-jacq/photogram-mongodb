<?php

${basename(__FILE__, '.php')} = function () {  
    if ($this->paramsExists(['user', 'password'])) {
        if ($this->auth->login($this->data['user'], $this->data['password'])) {
            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'message' => 'Authenticated',
                'redirect' => $this->getRedirect()
            ], 202);
        }
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
