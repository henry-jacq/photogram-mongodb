<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        $params = ['fname', 'website', 'job', 'bio', 'location', 'twitter', 'instagram'];

        if ($this->paramsExists($params)) {

            $result = $this->user->updateUser(
                $this->getUserId(), 
                $this->data
            );

            $msg = "Not Updated";

            if ($result->getModifiedCount() > 0) {
                $msg = 'Updated';
            }

            return $this->response([
                'message' => $msg
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
