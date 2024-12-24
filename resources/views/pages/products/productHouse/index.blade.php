@extends('layouts.layouts')

@section('page_title')
    Thống kê số tiền đã mua
@endsection

@section('content')
<div class="container">
    <h2>Thống kê số tiền đã mua</h2>

    <!-- Dropdown chọn loại thống kê -->
    <div>
        <label for="statsType">Chọn thống kê theo:</label>
        <select id="statsType" class="form-control" onchange="fetchStatistics()">
            <option value="day">Ngày</option>
            <option value="month">Tháng</option>
            <option value="year">Năm</option>
        </select>
    </div>

    <!-- Biểu đồ sẽ được hiển thị ở đây -->
    <canvas id="moneyChart" width="400" height="200"></canvas>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let moneyChart = null;

    // Hàm lấy dữ liệu thống kê từ API và vẽ biểu đồ
    function fetchStatistics() {
        const statsType = document.getElementById('statsType').value;

        // Gọi API để lấy dữ liệu thống kê từ route getStatistics
        $.ajax({
            url: '{{ route('getStatistics') }}',
            method: 'GET',
            data: { type: statsType },
            success: function(response) {
                console.log(response);  // Xem dữ liệu nhận được từ API

                let labels = [];
                let totalCosts = [];

                if (statsType === 'month') {
                    // Dữ liệu theo tháng (kết hợp tháng và năm)
                    labels = response.map(item => `Tháng ${item.month} - Năm ${item.year}`);
                    totalCosts = response.map(item => parseFloat(item.total_cost));
                } else if (statsType === 'year') {
                    // Dữ liệu theo năm
                    labels = response.map(item => `Năm ${item.year}`);
                    totalCosts = response.map(item => parseFloat(item.total_cost));
                } else {
                    // Dữ liệu theo ngày
                    labels = response.map(item => item.date);  // Ngày cụ thể
                    totalCosts = response.map(item => parseFloat(item.total_cost));
                }

                // Nếu biểu đồ đã tồn tại, xóa nó đi để vẽ lại
                if (moneyChart) {
                    moneyChart.destroy();
                }

                // Vẽ biểu đồ với Chart.js
                var ctx = document.getElementById('moneyChart').getContext('2d');
                moneyChart = new Chart(ctx, {
                    type: 'line', // Loại biểu đồ (có thể thay đổi thành 'bar', 'pie' v.v)
                    data: {
                        labels: labels, // Nhãn cho trục x (ngày, tháng, năm)
                        datasets: [{
                            label: 'Số tiền đã mua (VND)',
                            data: totalCosts, // Dữ liệu cho trục y
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: statsType === 'day' ? 'Ngày' : statsType === 'month' ? 'Tháng' : 'Năm'
                                },
                                ticks: {
                                    autoSkip: true,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Số tiền (VND)'
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        return value.toLocaleString(); // Hiển thị số với dấu phân cách hàng nghìn
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    // Gọi hàm fetchStatistics lần đầu khi trang load
    $(document).ready(function() {
        fetchStatistics();
    });
</script>
@endsection
