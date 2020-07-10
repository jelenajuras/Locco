<?php

    $vars = Session::all();
    foreach ($vars as $key => $value) {
        switch($key) {
            case 'success':
            case 'error':
            case 'warning':
            case 'info':
            if($key == 'error'){
              $key = 'danger';
            }
                ?>
                <div class="row" style="z-index: 1; position:fixed; top:0;">
                    <div class="alert alert-{{ $key }} alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>{{ ucfirst($key) }}:</strong> {!! $value !!}
                    </div>
                </div>
                <?php
                Session::forget($key);
                break;
            default:
        }
    }

?>
