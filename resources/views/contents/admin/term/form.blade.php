@extends('layouts.admin')

@section('content')
    <!-- Create Form Card -->
    <div class="col-12">
        <div class="card shadow mb-4 border-bottom-primary">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('term Form') }}</h6>
                <div class="dropdown no-arrow">
                    <x-BackButton />
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="text-center">

                    @if (isset($term))
                        <form class="user" method="POST" action="{{ route('term.update', $term->id) }}">
                            @method('patch')
                        @else
                            <form class="user" method="POST" action="{{ route('term.store') }}">
                    @endif

                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input name="title" required type="text" class="form-control form-control-user"
                                id="title" placeholder="Title" value="{{ $term->title ?? '' }}">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <input name="image" required type="text" class="form-control form-control-user"
                                id="image" placeholder="Image" value="{{ $term->image ?? '' }}">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Department-Course Dropdown Component -->
                    @livewire('forms.department-course-drop-down', [
                        'department_id' => $term->department_id ?? null,
                        'course_id' => $term->course_id ?? null,
                    ])

                    <!-- Add required to dropdown fields -->
                    <script>
                        document.addEventListener('livewire:load', function() {
                            const departmentDropdown = document.querySelector('[name="department_id"]');
                            const courseDropdown = document.querySelector('[name="course_id"]');

                            if (departmentDropdown) {
                                departmentDropdown.setAttribute('required', 'required');
                            }
                            if (courseDropdown) {
                                courseDropdown.setAttribute('required', 'required');
                            }
                        });
                    </script>

                    <div class="form-group">
                        <textarea name="description" type="text" class="form-control form-control-user editor" id="description"
                            placeholder="Description" required>{{ $term->description ?? '' }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-user btn-block" value="{{ __('Save') }}">
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
