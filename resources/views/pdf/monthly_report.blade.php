<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Monthly Report</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      line-height: 1.6;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }
    .container {
      width: 80%;
      margin: 20px auto;
      background: #fff;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .header {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .header h1 {
      font-size: 24px;
      margin: 0;
    }
    .header p {
      font-size: 14px;
      color: #555;
    }
    .section {
      margin-bottom: 20px;
    }
    .section h2 {
      font-size: 20px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 5px;
      color: #333;
      margin-bottom: 15px;
    }
    .section p {
      font-size: 16px;
      margin: 5px 0;
      color: #555;
    }
    .list {
      margin-top: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      overflow: hidden;
    }
    .list-item {
      display: flex;
      justify-content: space-between;
      padding: 10px 15px;
      border-bottom: 1px solid #ddd;
      background-color: #fdfdfd;
    }
    .list-item:nth-child(even) {
      background-color: #f6f6f6;
    }
    .list-item:last-child {
      border-bottom: none;
    }
    .list-item span {
      font-size: 16px;
      color: #333;
    }
    .footer {
      text-align: center;
      font-size: 14px;
      color: #888;
      margin-top: 20px;
      border-top: 1px solid #ddd;
      padding-top: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Monthly Report</h1>
      <p>Prepared for: {{ now()->format('F Y') }}</p>
    </div>

    <div class="section">
      <h2>Monthly Total Earnings for {{ $currentMonth }}</h2>
      <p>Total Earnings: <strong>PHP {{ number_format($totalEarningsThisMonth, 2) }}</strong></p>
    </div>

    <div class="section">
      <h2>Client Visits in {{ $currentMonth }}</h2>
      <div class="list">
        @foreach($clientVisits as $visit)
          <div class="list-item">
            <span>{{ $visit->client->first_name }} {{ $visit->client->last_name }}</span>
            <span>{{ $visit->visit_count }} visits</span>
          </div>
        @endforeach
      </div>
    </div>

    <div class="section">
      <h2>Total Visits This {{ $currentMonth }}</h2>
      <div class="list">
        <div class="list-item">
          <span>Walk-in Visits:</span>
          <span>{{ $walkInVisits }}</span>
        </div>
        <div class="list-item">
          <span>Monthly Visits:</span>
          <span>{{ $monthlyVisits }}</span>
        </div>
        <div class="list-item">
          <span>Total Visits:</span>
          <span>{{ $walkInVisits + $monthlyVisits }}</span>
        </div>
      </div>
    </div>

    <div class="footer">
      <p>Generated on {{ now()->format('F d, Y') }} | {{ now()->format('h:i A') }}</p>
      <p>&copy; {{ now()->year }} FLEX. All rights reserved.</p>
    </div>
  </div>
</body>
</html>
