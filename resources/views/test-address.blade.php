<!DOCTYPE html>
<html>
<head>
    <title>Test Address Data</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .result { margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 3px; }
        .error { background: #f8d7da; color: #721c24; }
        .success { background: #d4edda; color: #155724; }
        select { margin: 10px 0; padding: 5px; min-width: 200px; }
        button { padding: 10px 15px; margin: 5px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Test Address Data Loading</h1>
    
    <div class="test-section">
        <h2>Database Check</h2>
        <div class="result">
            <strong>Total Cities:</strong> {{ \App\Models\City::count() }}<br>
            <strong>Total Provinces:</strong> {{ \App\Models\Province::count() }}<br>
            <strong>Total Wards:</strong> {{ \App\Models\Wards::count() }}
        </div>
        
        <h3>Sample Data:</h3>
        <div class="result">
            <strong>Sample Cities:</strong><br>
            @foreach(\App\Models\City::take(3)->get() as $city)
                ID: {{ $city->matp }} - {{ $city->name_city }}<br>
            @endforeach
        </div>
        
        <div class="result">
            <strong>Sample Provinces:</strong><br>
            @foreach(\App\Models\Province::take(3)->get() as $province)
                ID: {{ $province->maqh }} - {{ $province->name_quanhuyen }} (City: {{ $province->matp }})<br>
            @endforeach
        </div>
        
        <div class="result">
            <strong>Sample Wards:</strong><br>
            @foreach(\App\Models\Wards::take(5)->get() as $ward)
                ID: {{ $ward->xaid }} - {{ $ward->name_xaphuong }} (Province: {{ $ward->maqh }})<br>
            @endforeach
        </div>
    </div>
    
    <div class="test-section">
        <h2>Live Address Testing</h2>
        
        <div>
            <label>Chọn Tỉnh/Thành phô:</label><br>
            <select id="testCity">
                <option value="0">Chọn tỉnh thành phố</option>
                @foreach(\App\Models\City::orderBy('matp', 'ASC')->get() as $city)
                    <option value="{{ $city->matp }}">{{ $city->name_city }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label>Chọn Quận/Huyện:</label><br>
            <select id="testProvince">
                <option value="0">Chọn quận huyện</option>
            </select>
        </div>
        
        <div>
            <label>Chọn Xã/Phường:</label><br>
            <select id="testWards">
                <option value="0">Chọn xã phường</option>
            </select>
        </div>
        
        <div id="testResult" class="result"></div>
    </div>
    
    <div class="test-section">
        <h2>Direct API Test</h2>
        <button onclick="testProvinceAPI()">Test Province API</button>
        <button onclick="testWardsAPI()">Test Wards API</button>
        <div id="apiResult" class="result"></div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $('#testCity').on('change', function(){
            var cityId = $(this).val();
            $('#testProvince').html('<option value="0">Chọn quận huyện</option>');
            $('#testWards').html('<option value="0">Chọn xã phường</option>');
            
            if(cityId != '0') {
                $('#testResult').html('Loading provinces for city: ' + cityId);
                
                $.ajax({
                    url: '/get-delivery-home',
                    method: 'POST',
                    data: { action: 'nameCity', ma_id: cityId, _token: '{{ csrf_token() }}' },
                    success: function(data) {
                        $('#testProvince').html(data);
                        $('#testResult').html('<div class="success">✅ Provinces loaded successfully</div>');
                    },
                    error: function(xhr, status, error) {
                        $('#testResult').html('<div class="error">❌ Error loading provinces: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
                    }
                });
            }
        });
        
        $('#testProvince').on('change', function(){
            var provinceId = $(this).val();
            $('#testWards').html('<option value="0">Chọn xã phường</option>');
            
            if(provinceId != '0') {
                $('#testResult').html('Loading wards for province: ' + provinceId);
                
                $.ajax({
                    url: '/get-delivery-home',
                    method: 'POST',
                    data: { action: 'nameProvince', ma_id: provinceId, _token: '{{ csrf_token() }}' },
                    success: function(data) {
                        $('#testWards').html(data);
                        $('#testResult').html('<div class="success">✅ Wards loaded successfully</div>');
                    },
                    error: function(xhr, status, error) {
                        $('#testResult').html('<div class="error">❌ Error loading wards: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
                    }
                });
            }
        });
        
        function testProvinceAPI() {
            $('#apiResult').html('Testing province API...');
            $.ajax({
                url: '/get-delivery-home',
                method: 'POST',
                data: { action: 'nameCity', ma_id: '01', _token: '{{ csrf_token() }}' },
                success: function(data) {
                    $('#apiResult').html('<div class="success">✅ Province API Response:<br><pre>' + data + '</pre></div>');
                },
                error: function(xhr, status, error) {
                    $('#apiResult').html('<div class="error">❌ Province API Error: ' + error + '<br>Status: ' + status + '<br>Response: ' + xhr.responseText + '</div>');
                }
            });
        }
        
        function testWardsAPI() {
            $('#apiResult').html('Testing wards API...');
            $.ajax({
                url: '/get-delivery-home',
                method: 'POST',
                data: { action: 'nameProvince', ma_id: '1', _token: '{{ csrf_token() }}' },  
                success: function(data) {
                    $('#apiResult').html('<div class="success">✅ Wards API Response:<br><pre>' + data + '</pre></div>');
                },
                error: function(xhr, status, error) {
                    $('#apiResult').html('<div class="error">❌ Wards API Error: ' + error + '<br>Status: ' + status + '<br>Response: ' + xhr.responseText + '</div>');
                }
            });
        }
    </script>
</body>
</html>
