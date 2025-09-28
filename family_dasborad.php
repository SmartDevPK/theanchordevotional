<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Resources Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
        }

        body {
            background-color: #f8f9fc;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .sidebar .nav-link.active {
            color: #fff;
            font-weight: 700;
        }

        .sidebar .nav-link:hover {
            color: #fff;
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .resource-image-preview {
            max-height: 150px;
            object-fit: cover;
        }

        .category-tabs .nav-link {
            color: #6c757d;
        }

        .category-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-white">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-primary">Family Resources Admin</h4>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">
                                <i class="fas fa-fw fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#hero-section" data-bs-toggle="tab">
                                <i class="fas fa-fw fa-image me-2"></i>Hero Section
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#resources" data-bs-toggle="tab">
                                <i class="fas fa-fw fa-book me-2"></i>Resources
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#categories" data-bs-toggle="tab">
                                <i class="fas fa-fw fa-tags me-2"></i>Categories
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <h2 class="h4 mb-4">Dashboard Overview</h2>
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Resources</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"
                                                    id="total-resources">24</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-book fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Total Downloads</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"
                                                    id="total-downloads">1,248</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-download fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Categories</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"
                                                    id="total-categories">5</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Section Tab -->
                    <div class="tab-pane fade" id="hero-section">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2 class="h4">Hero Section Management</h2>
                        </div>
                        <form id="heroForm">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Hero Content</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="heroTitle" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="heroTitle"
                                            value="Family & Life Stage Resources">
                                    </div>
                                    <div class="mb-3">
                                        <label for="heroDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="heroDescription"
                                            rows="3">Discover helpful resources tailored for every stage of life - from children to teens, singles to married couples.</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="heroButtonText" class="form-label">Button Text</label>
                                        <input type="text" class="form-control" id="heroButtonText"
                                            value="Explore Resources">
                                    </div>
                                    <div class="mb-3">
                                        <label for="heroImage" class="form-label">Background Image</label>
                                        <input class="form-control" type="file" id="heroImage">
                                        <div class="mt-2">
                                            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                                                alt="Current Hero Image" class="img-fluid resource-image-preview"
                                                id="heroImagePreview">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Resources Tab -->
                    <div class="tab-pane fade" id="resources">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2 class="h4">Resource Management</h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                                <i class="fas fa-plus me-1"></i> Add New Resource
                            </button>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs category-tabs" id="resourceTabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#allResources">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#childrenResources">Children</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#teensResources">Teens</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#singlesResources">Singles</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#marriedResources">Married</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#parentsResources">Parents</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="allResources">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Title</th>
                                                        <th>Category</th>
                                                        <th>Description</th>
                                                        <th>File</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
                                                                class="resource-image-preview"></td>
                                                        <td>Bible Story Coloring Book</td>
                                                        <td><span class="badge bg-primary">Children</span></td>
                                                        <td>25 coloring pages featuring key Bible stories...</td>
                                                        <td>coloring-book.pdf (5.1MB)</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary edit-resource"
                                                                data-id="1"><i class="fas fa-edit"></i></button>
                                                            <button class="btn btn-sm btn-danger delete-resource"
                                                                data-id="1"><i class="fas fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    <!-- More rows would be dynamically generated -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Other category tabs would have similar structure -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Tab -->
                    <div class="tab-pane fade" id="categories">
                        <div
                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2 class="h4">Category Management</h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus me-1"></i> Add New Category
                            </button>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Icon</th>
                                                <th>Resources</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Children</td>
                                                <td><i class="fas fa-child"></i></td>
                                                <td>6</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-category" data-id="1"><i
                                                            class="fas fa-edit"></i></button>
                                                    <button class="btn btn-sm btn-danger delete-category" data-id="1"><i
                                                            class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <!-- More rows would be dynamically generated -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Resource Modal -->
    <div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addResourceModalLabel">Add New Resource</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="resourceForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="resourceTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="resourceTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="resourceCategory" class="form-label">Category</label>
                            <select class="form-select" id="resourceCategory" required>
                                <option value="">Select Category</option>
                                <option value="children">Children</option>
                                <option value="teens">Teens</option>
                                <option value="singles">Singles</option>
                                <option value="married">Married</option>
                                <option value="parents">Parents</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="resourceDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="resourceDescription" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="resourceImage" class="form-label">Featured Image</label>
                                <input class="form-control" type="file" id="resourceImage" accept="image/*">
                                <div class="mt-2">
                                    <img src="" alt="Image Preview" class="img-fluid resource-image-preview d-none"
                                        id="resourceImagePreview">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="resourceFile" class="form-label">Resource File (PDF)</label>
                                <input class="form-control" type="file" id="resourceFile" accept=".pdf">
                                <div class="mt-2">
                                    <span id="resourceFileName" class="text-muted">No file selected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Resource</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoryForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoryIcon" class="form-label">Icon (Font Awesome class)</label>
                            <input type="text" class="form-control" id="categoryIcon" placeholder="fas fa-icon"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Image preview for resource image
            $('#resourceImage').change(function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#resourceImagePreview').attr('src', e.target.result).removeClass('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Display selected file name
            $('#resourceFile').change(function () {
                const fileName = $(this).val().split('\\').pop();
                $('#resourceFileName').text(fileName || 'No file selected');
            });

            // Form submissions would be handled here
            $('#resourceForm').submit(function (e) {
                e.preventDefault();
                // AJAX call to save resource
                alert('Resource saved successfully!');
                $('#addResourceModal').modal('hide');
                // Refresh resource list
            });

            // Similar handlers for other forms
        });
    </script>
</body>

</html>