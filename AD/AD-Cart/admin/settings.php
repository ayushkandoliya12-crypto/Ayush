<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .list-group-custom .list-group-item {
            border: none;
            border-radius: 8px !important;
            padding: 12px 18px;
            margin-bottom: 6px;
            font-weight: 500;
            color: #555;
            transition: all 0.2s ease;
        }
        .list-group-custom .list-group-item:hover {
            background-color: #eef2f7;
            color: #0d6efd;
        }
        .list-group-custom .list-group-item.active {
            background-color: #e7f1ff;
            color: #0d6efd;
            border-left: 4px solid #0d6efd;
        }
        .profile-avatar {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border: 3px solid #0d6efd;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .btn-primary {
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">Account Settings</h3>
            <p class="text-muted mb-0">Manage your profile, security, and notification preferences.</p>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card card-custom p-3">
                <div class="list-group list-group-custom" id="settings-tab" role="tablist">
                    <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list" role="tab">
                        <i class="fa-regular fa-user me-2"></i> Profile Info
                    </a>
                    <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="list" role="tab">
                        <i class="fa-solid fa-shield-halved me-2"></i> Security & Password
                    </a>
                    <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list" role="tab">
                        <i class="fa-regular fa-bell me-2"></i> Notifications
                    </a>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-lg-9">
            <div class="card card-custom p-4">
                <div class="tab-content" id="settings-tabContent">
                    
                    <!-- Profile Settings -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <h5 class="fw-bold mb-3">Profile Details</h5>
                        <hr class="text-muted opacity-25">
                        
                        <!-- Avatar Section -->
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=0D6EFD&color=fff&size=128" alt="Profile" class="rounded-circle profile-avatar">
                            <div>
                                <button class="btn btn-sm btn-outline-primary mb-1"><i class="fa-solid fa-upload me-1"></i> Change Photo</button>
                                <div class="form-text">JPG, GIF or PNG. Max size 2MB</div>
                            </div>
                        </div>

                        <form action="update_profile.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Full Name</label>
                                    <input type="text" name="fullname" class="form-control" value="John Doe">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="john@example.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-medium">Bio</label>
                                    <textarea class="form-control" rows="3" placeholder="Tell us a little about yourself..."></textarea>
                                </div>
                            </div>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Settings -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <h5 class="fw-bold mb-3" >Change Password</h5>
                        <hr class="text-muted opacity-25">

                        <form action="update_password.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-medium">Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••">
                                </div>
                            </div>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-lock me-1"></i> Update Password</button>
                            </div>
                        </form>
                    </div>

                    <!-- Notification Settings -->
                    <div class="tab-pane fade" id="notifications" role="tabpanel">
                        <h5 class="fw-bold mb-3">Preferences</h5>
                        <hr class="text-muted opacity-25">

                        <form action="update_notifications.php" method="POST">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded mb-3">
                                <div>
                                    <h6 class="mb-1 fw-semibold">Email Notifications</h6>
                                    <p class="small text-muted mb-0">Receive account updates and security alerts via email.</p>
                                </div>
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded mb-3">
                                <div>
                                    <h6 class="mb-1 fw-semibold">SMS Alerts</h6>
                                    <p class="small text-muted mb-0">Get instant SMS updates for important security notifications.</p>
                                </div>
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" id="smsNotif">
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Save Preferences</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>