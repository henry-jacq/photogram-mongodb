<?php

if ($this->isAuthenticated()) :
?>
    <div class="scroll-up position-fixed bottom-0 end-0 mb-3 me-3">
        <button id="scroll-top-btn" class="btn btn-prime rounded-circle p-2 border-none d-none" type="button">
            <i class="fas fa-chevron-up px-1"></i>
        </button>
    </div>
<?php
endif;
?>