<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['fullname', 'username', 'email', 'password'])) {
        if ($this->auth->register($this->data)) {
            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'result' => true
            ], 201);
        }
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'result' => false
        ], 406);
    }
};
