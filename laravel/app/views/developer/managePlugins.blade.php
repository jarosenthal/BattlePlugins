@extends('layouts.master')
@section('content')
<div class="content-section-a">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h2>Manage Plugins</h2>
				<p>Add, edit, or delete plugins from this page. You may only add Battle<em>Plugins</em> to this list. Plugins will go through an approval process before being added to the list. Adding a plugin to the website enables the following features:</p>
				<ul>
					<li><a href="http://ci.battleplugins.com/" target="_blank">Jenkins</a> tracking via the homepage and plugin list</li>
					<li>Statistics tracking for plugins using <a href="https://github.com/alkarinv/BattlePluginsAPI" target="_blank">BattlePluginsAPI</a></li>
					<li>Advertising of your plugin through BattlePlugins</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="content-section-b">
	<div class="container">
		@foreach($plugins as $plugin)
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-4"><h1>{{ $plugin->name }}</h1></div>
							<div class="col-lg-7"><input type="text" class="form-control input-xl" placeholder="Bukkit Slug" value="{{ $plugin->bukkit }}" disabled /></div>
							<div class="col-lg-1 text-danger"><a href="#deletePlugin" data-plugin="{{ $plugin->name }}"><i class="fa fa-times-circle fa-5x"></i></a></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
@stop
