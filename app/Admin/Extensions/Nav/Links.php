<?php

namespace App\Admin\Extensions\Nav;
use App\Models\Ads;
use App\Models\Feedback;

class Links
{
    public function __toString()
    {
        
        $host = request()->server('HTTP_HOST');
        $prot = request()->server("REQUEST_SCHEME");
        $ads = Ads::where('activ','0')->get();
        $feedback = Feedback::where('new','1')->get();

        $new_ads = count($ads) ? "<li><a href=\"/admin/ads\"><i class=\"fa fa-bell-o\"></i><span class=\"label label-warning\">".count($ads)."</span></a></li>" : "";
        $new_ads .= count($feedback) ? "<li><a href=\"/admin/feedback\"><i class=\"fa fa-bullhorn\"></i><span class=\"label label-warning\">".count($feedback)."</span></a></li>" : "";

        return <<<HTML

<!--<li>
    <a href="#">
      <i class="fa fa-envelope-o"></i>
      <span class="label label-success">4</span>
    </a>
</li>-->

{$new_ads}
<!--
<li>
    <a href="#">
      <i class="fa fa-flag-o"></i>
      <span class="label label-danger">9</span>
    </a>
</li>-->
        
<li>
    <a href="{$prot}://{$host}/" target="_blank">
        <span class="label label-success"></span>
        Web site <i class="fa fa-external-link" aria-hidden="true"></i>
    </a>
</li>

HTML;
    }
}