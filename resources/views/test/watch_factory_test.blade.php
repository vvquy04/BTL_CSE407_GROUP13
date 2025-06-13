<!DOCTYPE html>
<html>
<head>
    <title>Watch Factory Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Watch Factory Test</h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Men's Watch</h4>
                    </div>
                    <div class="card-body">
                        <form id="menWatchForm">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="Classic Men's Watch">
                            </div>
                            <div class="mb-3">
                                <label>Price</label>
                                <input type="number" class="form-control" name="price" value="299.99">
                            </div>
                            <div class="mb-3">
                                <label>Brand</label>
                                <input type="text" class="form-control" name="brand" value="Rolex">
                            </div>
                            <div class="mb-3">
                                <label>Model</label>
                                <input type="text" class="form-control" name="model" value="Submariner">
                            </div>
                            <div class="mb-3">
                                <label>Movement</label>
                                <input type="text" class="form-control" name="movement" value="Automatic">
                            </div>
                            <button type="submit" class="btn btn-primary">Create Men's Watch</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Smart Watch</h4>
                    </div>
                    <div class="card-body">
                        <form id="smartWatchForm">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="Smart Watch Pro">
                            </div>
                            <div class="mb-3">
                                <label>Price</label>
                                <input type="number" class="form-control" name="price" value="399.99">
                            </div>
                            <div class="mb-3">
                                <label>Brand</label>
                                <input type="text" class="form-control" name="brand" value="Apple">
                            </div>
                            <div class="mb-3">
                                <label>Model</label>
                                <input type="text" class="form-control" name="model" value="Series 7">
                            </div>
                            <div class="mb-3">
                                <label>OS</label>
                                <input type="text" class="form-control" name="os" value="watchOS">
                            </div>
                            <div class="mb-3">
                                <label>Battery Life</label>
                                <input type="text" class="form-control" name="battery_life" value="18 hours">
                            </div>
                            <div class="mb-3">
                                <label>Features</label>
                                <input type="text" class="form-control" name="features" value="Heart Rate, GPS, ECG">
                            </div>
                            <button type="submit" class="btn btn-primary">Create Smart Watch</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="result" class="mt-4"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#menWatchForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                
                $.ajax({
                    url: '/api/watches/men',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#result').html(`
                            <div class="alert alert-success">
                                <h4>Men's Watch Created Successfully!</h4>
                                <pre>${JSON.stringify(response, null, 2)}</pre>
                            </div>
                        `);
                    },
                    error: function(xhr) {
                        $('#result').html(`
                            <div class="alert alert-danger">
                                <h4>Error Creating Men's Watch</h4>
                                <pre>${JSON.stringify(xhr.responseJSON, null, 2)}</pre>
                            </div>
                        `);
                    }
                });
            });

            $('#smartWatchForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                
                $.ajax({
                    url: '/api/watches/smart',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#result').html(`
                            <div class="alert alert-success">
                                <h4>Smart Watch Created Successfully!</h4>
                                <pre>${JSON.stringify(response, null, 2)}</pre>
                            </div>
                        `);
                    },
                    error: function(xhr) {
                        $('#result').html(`
                            <div class="alert alert-danger">
                                <h4>Error Creating Smart Watch</h4>
                                <pre>${JSON.stringify(xhr.responseJSON, null, 2)}</pre>
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
</body>
</html> 