<?php

// Home
//Breadcrumbs::register('home', function ($breadcrumbs, $ad) {
//    $breadcrumbs->push('Home', route('home'));
//    $breadcrumbs->push('Search', route('search'));
//    $breadcrumbs->push($ad->name, route('advertise'));
//});

//// Home > About
//Breadcrumbs::register('about', function ($breadcrumbs) {
//    $breadcrumbs->parent('home');
//    $breadcrumbs->push('About', route('about'));
//});
//
//// Home > Blog
//Breadcrumbs::register('blog', function ($breadcrumbs) {
//    $breadcrumbs->parent('home');
//    $breadcrumbs->push('Blog', route('blog'));
//});
//
//// Home > Blog > [Category]
//Breadcrumbs::register('category', function ($breadcrumbs, $category) {
//    $breadcrumbs->parent('blog');
//    $breadcrumbs->push($category->title, route('category', $category->id));
//});
//
//// Home > Blog > [Category] > [Post]
//Breadcrumbs::register('post', function ($breadcrumbs, $post) {
//    $breadcrumbs->parent('category', $post->category);
//    $breadcrumbs->push($post->title, route('post', $post));
//});