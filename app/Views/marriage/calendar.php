<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Registration Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .calendar-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .calendar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .calendar-nav button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .calendar-nav button:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e0e0e0;
            padding: 1px;
        }
        
        .calendar-day-header {
            background: #f8f9fa;
            padding: 15px 5px;
            text-align: center;
            font-weight: bold;
            color: #666;
        }
        
        .calendar-day {
            background: white;
            min-height: 120px;
            padding: 8px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .calendar-day:hover {
            background: #f8f9fa;
        }
        
        .calendar-day.other-month {
            background: #f8f9fa;
            color: #ccc;
        }
        
        .calendar-day.blocked {
            background: #ffebee;
            color: #d32f2f;
        }
        
        .calendar-day.today {
            background: #e3f2fd;
            border: 2px solid #2196f3;
        }
        
        .day-number {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .slot-info {
            font-size: 10px;
            padding: 2px 4px;
            margin: 1px 0;
            border-radius: