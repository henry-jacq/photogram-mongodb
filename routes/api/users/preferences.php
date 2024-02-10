<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('GET')) {

        // To change user theme
        if ($this->paramsExists(['theme'])) {

            $id = $this->getUserId();
            $theme = $this->data['theme'] ?? "dark";

            $result = $this->user->updatePreference($id, "theme", $theme);

            return $this->response([
                'message' => $result
            ], 200);
        }

        // To change user home view mode
        if ($this->paramsExists(['view'])) {
            $id = $this->getUserId();
            $mode = $this->data['view'] ?? "grid";
            
            $result = $this->user->updatePreference($id, "view", $mode);
            
            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
