<div>
	<ol class="cd-breadcrumb triangle">
		@foreach($gc->breadcrumb as $a)
		<li @if($a['current']) class="current" @endif ><a href="{!!$a['href']!!}">{!!$a['label']!!}</a></li>
		@endforeach
	</ol>
</div>