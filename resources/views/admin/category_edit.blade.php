@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Category infomation</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route('admin.index')}}">
                                                <div class="text-tiny">Dashboard</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.categories')}}">
                                                <div class="text-tiny">Categories</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">New Category</div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- new-category -->
                                <div class="wg-box">
                                    <form class="form-new-product form-style-1" action="{{route('admin.category.update')}}" method="POST"
                                        enctype="multipart/form-data">
                                         @csrf
                                        @method('PUT')
                                        <input type="hidden" value="{{$category->id}}" name="edit_id">
                                        <fieldset class="name">
                                            <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Category name" name="name" value="{{$category->name}}"
                                                tabindex="0"  aria-required="true" required="">
                                        </fieldset>
                                        @error('name')
                                            <span class="alert alert-danger text-center">{{$message}}</span>
                                        @enderror
                                        <fieldset class="name">
                                            <div class="body-title">Category Slug <span class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="text" placeholder="Category Slug" name="slug" value="{{$category->slug}}"
                                                tabindex="0" aria-required="true" required="">
                                        </fieldset>
                                        @error('slug')
                                            <span class="alert alert-danger text-center">{{$message}}</span>
                                        @enderror
                                        <fieldset>
                                            <div class="body-title">Upload images <span class="tf-color-1">*</span>
                                            </div>
                                            <div class="upload-image flex-grow">
                                                 @if ($category->image)
                                              <div class="upload-image flex-grow">
                                                <div class="item" id="imgpreview">
                                                    <img src="{{asset('uploads/categories')}}/{{$category->image}}" class="effect8" alt="">
                                                </div>  
                                            @endif
                                                <div id="upload-file" class="item up-load">
                                                    <label class="uploadfile" for="myFile">
                                                        <span class="icon">
                                                            <i class="icon-upload-cloud"></i>
                                                        </span>
                                                        <span class="body-text">Drop your images here or select <span
                                                                class="tf-color">click to browse</span></span>
                                                        <input type="file" id="myFile" name="image" accept="image/*">
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                        @error('image')
                                            <span class="alert alert-danger text-center">{{$message}}</span>
                                        @enderror
                                        <div class="bot">
                                            <div></div>
                                            <button class="tf-button w208" type="submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
@endsection