<?php
// Include database connection
require_once 'dbconfig.php';

// Initialize variables to prevent undefined variable errors
$asset = [
    'AssetID' => '',
    'AssetName' => '',
    'SerialNumber' => '',
    'CatergoryID' => '',
    'SubCatergoryID' => '',
    'BrandID' => '',
    'UserID' => '',
    'LocationID' => '',
    'PuchaseDate' => '',
    'PurchaseCost' => '',
    'AssetTag' => '',
    'Description' => '',
    'OrderID' => '',
    'SupplierID' => '',
    'st' => ''
];

// Check if AssetID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $assetId = intval($_GET['id']);

    // Fetch existing asset details
    $stmt = $conn->prepare("SELECT * FROM assets WHERE AssetID = ?");
    $stmt->bind_param("i", $assetId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $asset = $result->fetch_assoc();
    } else {
        die("Asset not found");
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $assetId = isset($_POST['AssetID']) ? intval($_POST['AssetID']) : 0;
    
    // Prepare update statement
    $stmt = $conn->prepare("UPDATE assets SET 
    AssetName = ?, 
    SerialNumber = ?, 
    CatergoryID = ?, 
    SubCatergoryID = ?, 
    BrandID = ?, 
    UserID = ?, 
    LocationID = ?, 
    PuchaseDate = ?, 
    PurchaseCost = ?, 
    AssetTag = ?, 
    Description = ?, 
    OrderID = ?, 
    SupplierID = ?, 
    st = ? 
    WHERE AssetID = ?");

// Ensure exact match between type string and variables
$stmt->bind_param(
    "ssiiiiiisdsssis",  // Note the change here: one less 's'
    $_POST['AssetName'],
    $_POST['SerialNumber'],
    $_POST['CatergoryID'],
    $_POST['SubCatergoryID'],
    $_POST['BrandID'],
    $_POST['UserID'],
    $_POST['LocationID'],
    $_POST['PuchaseDate'],
    $_POST['PurchaseCost'],
    $_POST['AssetTag'],
    $_POST['Description'],
    $_POST['OrderID'],
    $_POST['SupplierID'],
    $_POST['st'],
    $assetId  // Last parameter
);

// Method 2: Safer approach with type casting
$assetName = strval($_POST['AssetName']);
$serialNumber = strval($_POST['SerialNumber']);
$categoryId = intval($_POST['CatergoryID']);
$subCategoryId = intval($_POST['SubCatergoryID']);
$brandId = intval($_POST['BrandID']);
$userId = intval($_POST['UserID']);
$locationId = intval($_POST['LocationID']);
$purchaseDate = $_POST['PuchaseDate'];
$purchaseCost = floatval($_POST['PurchaseCost']);
$assetTag = strval($_POST['AssetTag']);
$description = strval($_POST['Description']);
$orderId = intval($_POST['OrderID']);
$supplierId = intval($_POST['SupplierID']);
$status = strval($_POST['st']);

$stmt->bind_param(
    "ssiiiiiisdsssis",
    $assetName,
    $serialNumber,
    $categoryId,
    $subCategoryId,
    $brandId,
    $userId,
    $locationId,
    $purchaseDate,
    $purchaseCost,
    $assetTag,
    $description,
    $orderId,
    $supplierId,
    $status,
    $assetId
);

    // Handle file upload for images
    if (isset($_FILES['Images']) && $_FILES['Images']['error'] == 0) {
        $imageData = file_get_contents($_FILES['Images']['tmp_name']);
        
        // Prepare image update statement
        $imageStmt = $conn->prepare("UPDATE assets SET Images = ? WHERE AssetID = ?");
        $imageStmt->bind_param("bi", $imageData, $assetId);
        $imageStmt->send_long_data(0, $imageData);
        $imageStmt->execute();
        $imageStmt->close();
    }

    // Execute update
    if ($stmt->execute()) {
        // Redirect to asset list or show success message
        header("Location: productlist.php?success=1");
        exit();
    } else {
        $error = "Error updating asset: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fetch dropdown options
function fetchDropdownOptions($conn, $table, $valueColumn, $textColumn) {
    $options = [];
    $result = $conn->query("SELECT $valueColumn, $textColumn FROM $table");
    while ($row = $result->fetch_assoc()) {
        $options[$row[$valueColumn]] = $row[$textColumn];
    }
    return $options;
}

$categories = fetchDropdownOptions($conn, 'categories', 'CatergoryID', 'CategoryName');
$subcategories = fetchDropdownOptions($conn, 'subcatergory', 'SubCatergoryID', 'CatergoryName');
$brands = fetchDropdownOptions($conn, 'brands', 'BrandID', 'BrandName');
$users = fetchDropdownOptions($conn, 'users', 'UserID', 'fullname');
$locations = fetchDropdownOptions($conn, 'location', 'LocationID', 'LocationName');
$suppliers = fetchDropdownOptions($conn, 'suppliers', 'SupplierID', 'SupplierName');


function updateAssetStatus($conn, $assetId, $status) {
    // Validate status
    $validStatuses = ['Available', 'InUse', 'UnderMaintainance', 'Disposed', 'DueForDisposal'];
    
    if (!in_array($status, $validStatuses)) {
        throw new InvalidArgumentException("Invalid asset status: $status");
    }

    // Prepared statement for status update
    $stmt = $conn->prepare("UPDATE assets SET st = ? WHERE AssetID = ?");
    $stmt->bind_param("si", $status, $assetId);
    
    if (!$stmt->execute()) {
        // Log detailed error
        error_log("Status Update Failed: " . $stmt->error);
        return false;
    }
    
    return true;
}

// Example usage
try {
    $result = updateAssetStatus($conn, $assetId, $_POST['st']);
    if ($result) {
        echo "Status successfully updated!";
    } else {
        echo "Status update failed.";
    }
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="POS - Bootstrap Admin Template">
<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
<meta name="author" content="Dreamguys - Bootstrap Admin Template">
<meta name="robots" content="noindex, nofollow">
<title>TITAN</title>

<link rel="shortcut icon" type="image/x-icon" href="assets/img/LOGO (2).png">

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" href="assets/css/animate.css">

<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

<link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div id="global-loader">
<div class="whirly-loader"> </div>
</div>

<div class="main-wrapper">

<div class="header">

<div class="header-left active">
<a href="index.php" class="logo">
<img src="assets/img/LOGO (2).png" alt=""style="width: 85px;">
</a>
<a href="index.php" class="logo-small">
<img src="assets/img/LOGO (2).png" alt="">
</a>
<a id="toggle_btn" href="javascript:void(0);">
</a>
</div>

<a id="mobile_btn" class="mobile_btn" href="#sidebar">
<span class="bar-icon">
<span></span>
<span></span>
<span></span>
</span>
</a>

<ul class="nav user-menu">

<li class="nav-item">
<div class="top-nav-search">
<a href="javascript:void(0);" class="responsive-search">
<i class="fa fa-search"></i>
</a>
<form action="#">
<div class="searchinputs">
<input type="text" placeholder="Search Here ...">
<div class="search-addon">
<span><img src="assets/img/icons/closes.svg" alt="img"></span>
</div>
</div>
<a class="btn" id="searchdiv"><img src="assets/img/icons/search.svg" alt="img"></a>
</form>
</div>
</li>


<li class="nav-item dropdown has-arrow flag-nav">
<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
<img src="assets/img/flags/zim.png" alt="" height="20">
</a>
<div class="dropdown-menu dropdown-menu-right">
<a href="javascript:void(0);" class="dropdown-item">
<img src="assets/img/flags/zim.png" alt="" height="16"> English
</a>

</div>
</li>


<li class="nav-item dropdown">
<a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
<img src="assets/img/icons/notification-bing.svg" alt="img"> <span class="badge rounded-pill">4</span>
</a>
<div class="dropdown-menu notifications">
<div class="topnav-dropdown-header">
<span class="notification-title">Notifications</span>
<a href="javascript:void(0)" class="clear-noti"> Clear All </a>
</div>
<div class="noti-content">
<ul class="notification-list">
<li class="notification-message">
<a href="activities.php">
<div class="media d-flex">
<span class="avatar flex-shrink-0">
<img alt="" src="assets/img/profiles/avatar-02.jpg">
</span>
<div class="media-body flex-grow-1">
<p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>
<p class="noti-time"><span class="notification-time">4 mins ago</span></p>
</div>
</div>
</a>
</li>
<li class="notification-message">
<a href="activities.php">
<div class="media d-flex">
<span class="avatar flex-shrink-0">
<img alt="" src="assets/img/profiles/avatar-03.jpg">
</span>
<div class="media-body flex-grow-1">
<p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed the task name <span class="noti-title">Appointment booking with payment gateway</span></p>
<p class="noti-time"><span class="notification-time">6 mins ago</span></p>
</div>
</div>
</a>
</li>
<li class="notification-message">
<a href="activities.php">
<div class="media d-flex">
<span class="avatar flex-shrink-0">
<img alt="" src="assets/img/profiles/avatar-06.jpg">
</span>
<div class="media-body flex-grow-1">
<p class="noti-details"><span class="noti-title">Misty Tison</span> added <span class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span> to project <span class="noti-title">Doctor available module</span></p>
<p class="noti-time"><span class="notification-time">8 mins ago</span></p>
</div>
</div>
</a>
</li>
<li class="notification-message">
<a href="activities.php">
<div class="media d-flex">
<span class="avatar flex-shrink-0">
<img alt="" src="assets/img/profiles/avatar-17.jpg">
</span>
<div class="media-body flex-grow-1">
<p class="noti-details"><span class="noti-title">Rolland Webber</span> completed task <span class="noti-title">Patient and Doctor video conferencing</span></p>
<p class="noti-time"><span class="notification-time">12 mins ago</span></p>
</div>
</div>
</a>
</li>
<li class="notification-message">
<a href="activities.php">
<div class="media d-flex">
<span class="avatar flex-shrink-0">
<img alt="" src="assets/img/profiles/avatar-13.jpg">
</span>
<div class="media-body flex-grow-1">
<p class="noti-details"><span class="noti-title">Bernardo Galaviz</span> added new task <span class="noti-title">Private chat module</span></p>
<p class="noti-time"><span class="notification-time">2 days ago</span></p>
</div>
</div>
</a>
</li>
</ul>
</div>
<div class="topnav-dropdown-footer">
<a href="activities.php">View all Notifications</a>
</div>
</div>
</li>

<li class="nav-item dropdown has-arrow main-drop">
<a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
<span class="user-img"><img src="assets/img/User1.png" alt="">
<span class="status online"></span></span>
</a>
<div class="dropdown-menu menu-drop-user">
<div class="profilename">
<div class="profileset">
<span class="user-img"><img src="assets/img/profiles/avator1.jpg" alt="">
<span class="status online"></span></span>
<div class="profilesets">
<h6>John Doe</h6>
<h5>Admin</h5>
</div>
</div>
<hr class="m-0">
<a class="dropdown-item" href="profile.php"> <i class="me-2" data-feather="user"></i> My Profile</a>
<a class="dropdown-item" href="generalsettings.php"><i class="me-2" data-feather="settings"></i>Settings</a>
<hr class="m-0">
<a class="dropdown-item logout pb-0" href="signin.php"><img src="assets/img/icons/log-out.svg" class="me-2" alt="img">Logout</a>
</div>
</div>
</li>
</ul>


<div class="dropdown mobile-user-menu">
<a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
<div class="dropdown-menu dropdown-menu-right">
<a class="dropdown-item" href="profile.php">My Profile</a>
<a class="dropdown-item" href="generalsettings.php">Settings</a>
<a class="dropdown-item" href="signin.php">Logout</a>
</div>
</div>

</div>


<div class="sidebar" id="sidebar">
<div class="sidebar-inner slimscroll">
<div id="sidebar-menu" class="sidebar-menu">
<ul>
<li>
<a href="index.php"><img src="assets/img/icons/dashboard.svg" alt="img"><span> Dashboard</span> </a>
</li>
<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>Assets</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="productlist.php" class="active">Assets List</a></li>
<li><a href="addproduct.php">Add Asset</a></li>
<li><a href="categorylist.php">Category List</a></li>
<li><a href="addcategory.php">Add Category</a></li>
<li><a href="subcategorylist.php">Sub Category List</a></li>
<li><a href="subaddcategory.php">Add Sub Category</a></li>
<li><a href="brandlist.php">Brand List</a></li>
<li><a href="addbrand.php">Add Brand</a></li>
<li><a href="importproduct.php">Import Asset</a></li>
<li><a href="barcode.php">Print Barcode</a></li>
</ul>
</li>

<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span> Inventory</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="purchaselist.php">Inventory List</a></li>
<li><a href="addpurchase.php">Add Inventory</a></li>
<li><a href="importpurchase.php">Import Inventory</a></li>
</ul>
</li>

<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/quotation1.svg" alt="img"><span> Orders</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="quotationList.php">Orders List</a></li>
<li><a href="addquotation.php">Add Order</a></li>
</ul>
</li>
<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/transfer1.svg" alt="img"><span> Transfer</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="transferlist.php">Transfer List</a></li>
<li><a href="addtransfer.php">Add Transfer </a></li>
<li><a href="importtransfer.php">Import Transfer </a></li>
</ul>
</li>

<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span> People</span> <span class="menu-arrow"></span></a>
<ul>

<li><a href="supplierlist.php">Supplier List</a></li>
<li><a href="addsupplier.php">Add Supplier </a></li>
<li><a href="userlist.php">User List</a></li>
<li><a href="adduser.php">Add User</a></li>
<li><a href="storelist.php">Store List</a></li>
<li><a href="addstore.php">Add Store</a></li>
</ul>
</li>
<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/places.svg" alt="img"><span> Locations</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="newcountry.php">New Location</a></li>
<li><a href="countrieslist.php">Locations list</a></li>
<li><a href="newstate.php">New AP </a></li>
<li><a href="statelist.php">AP list</a></li>
</ul>
</li>


<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/time.svg" alt="img"><span> Report</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="purchaseorderreport.php">Purchase order report</a></li>
<li><a href="inventoryreport.php">Inventory Report</a></li>
<li><a href="salesreport.php">Sales Report</a></li>
<li><a href="invoicereport.php">Invoice Report</a></li>
<li><a href="purchasereport.php">Purchase Report</a></li>
<li><a href="supplierreport.php">Supplier Report</a></li>
<li><a href="customerreport.php">Customer Report</a></li>
</ul>
</li>
<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span> Users</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="newuser.php">New User </a></li>
<li><a href="userlists.php">Users List</a></li>
</ul>
</li>
<li class="submenu">
<a href="javascript:void(0);"><img src="assets/img/icons/settings.svg" alt="img"><span> Settings</span> <span class="menu-arrow"></span></a>
<ul>
<li><a href="generalsettings.php">General Settings</a></li>
<li><a href="emailsettings.php">Email Settings</a></li>
<li><a href="paymentsettings.php">Payment Settings</a></li>
<li><a href="currencysettings.php">Currency Settings</a></li>
<li><a href="grouppermissions.php">Group Permissions</a></li>
<li><a href="taxrates.php">Tax Rates</a></li>
</ul>
</li>
</ul>
</div>
</div>
</div>

<div class="page-wrapper">
<div class="content">
<div class="page-header">
<div class="page-title">
<h4>Asset Edit</h4>
<h6>Update your Asset</h6>
</div>
</div>

<?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="AssetID" value="<?php echo htmlspecialchars($asset['AssetID']); ?>">
                
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Asset Name -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Asset Name</label>
                                    <input type="text" name="AssetName" 
                                           value="<?php echo htmlspecialchars($asset['AssetName']); ?>" 
                                           class="form-control" required>
                                </div>
                            </div>

                            <!-- Serial Number -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Serial Number</label>
                                    <input type="text" name="SerialNumber" 
                                           value="<?php echo htmlspecialchars($asset['SerialNumber']); ?>" 
                                           class="form-control">
                                </div>
                            </div>

                            <!-- Category Dropdown -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="CatergoryID" class="form-control select2">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $id => $name): ?>
                                            <option value="<?php echo $id; ?>" 
                                                <?php echo ($asset['CatergoryID'] == $id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- More form fields following similar pattern -->
                            <!-- SubCategory -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Sub Category</label>
                                    <select name="SubCatergoryID" class="form-control select2">
                                        <option value="">Select Sub Category</option>
                                        <?php foreach ($subcategories as $id => $name): ?>
                                            <option value="<?php echo $id; ?>" 
                                                <?php echo ($asset['SubCatergoryID'] == $id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Brand -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Brand</label>
                                    <select name="BrandID" class="form-control select2">
                                        <option value="">Select Brand</option>
                                        <?php foreach ($brands as $id => $name): ?>
                                            <option value="<?php echo $id; ?>" 
                                                <?php echo ($asset['BrandID'] == $id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Responsible User -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Responsible User</label>
                                    <select name="UserID" class="form-control select2">
                                        <option value="">Select User</option>
                                        <?php foreach ($users as $id => $name): ?>
                                            <option value="<?php echo $id; ?>" 
                                                <?php echo ($asset['UserID'] == $id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Purchase Date -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Date</label>
                                    <input type="date" name="PuchaseDate" 
                                           value="<?php echo htmlspecialchars($asset['PuchaseDate']); ?>" 
                                           class="form-control">
                                </div>
                            </div>

                            <!-- Asset Tag -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Asset Tag</label>
                                    <input type="text" name="AssetTag" 
                                           value="<?php echo htmlspecialchars($asset['AssetTag']); ?>" 
                                           class="form-control" required>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="Description" class="form-control"><?php echo htmlspecialchars($asset['Description']); ?></textarea>
                                </div>
                            </div>

                            <!-- Purchase Cost -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Purchase Cost</label>
                                    <input type="number" step="0.01" name="PurchaseCost" 
                                           value="<?php echo htmlspecialchars($asset['PurchaseCost']); ?>" 
                                           class="form-control" required>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Location</label>
                                    <select name="LocationID" class="form-control select2">
                                        <option value="">Select Location</option>
                                        <?php foreach ($locations as $id => $name): ?>
                                            <option value="<?php echo $id; ?>" 
                                                <?php echo ($asset['LocationID'] == $id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Supplier -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select name="SupplierID" class="form-control select2">
                                        <option value="">Select Supplier</option>
                                        <?php foreach ($suppliers as $id => $name): ?>
                                            <option value="<?php echo $id; ?>" 
                                                <?php echo ($asset['SupplierID'] == $id) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="st" class="form-control select2" required>
                                        <option value="Available" <?php echo ($asset['st'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                        <option value="InUse" <?php echo ($asset['st'] == 'InUse') ? 'selected' : ''; ?>>In Use</option>
                                        <option value="UnderMaintainance" <?php echo ($asset['st'] == 'UnderMaintainance') ? 'selected' : ''; ?>>Under Maintenance</option>
                                        <option value="Disposed" <?php echo ($asset['st'] == 'Disposed') ? 'selected' : ''; ?>>Disposed</option>
                                        <option value="DueForDisposal" <?php echo ($asset['st'] == 'DueForDisposal') ? 'selected' : ''; ?>>Due for Disposal</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Product Image</label>
                                    <div class="image-upload">
                                        <input type="file" name="Images" accept="image/*">
                                        <div class="image-uploads">
                                            <img src="assets/img/icons/upload.svg" alt="img">
                                            <h4>Drag and drop a file to upload</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">Update Asset</button>
                                <a href="productlist.php" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
<script src="assets/js/jquery-3.6.0.min.js"></script>

<script src="assets/js/feather.min.js"></script>

<script src="assets/js/jquery.slimscroll.min.js"></script>

<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/dataTables.bootstrap4.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/plugins/select2/js/select2.min.js"></script>

<script src="assets/js/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
<script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="assets/js/script.js"></script>
</body>
</html>