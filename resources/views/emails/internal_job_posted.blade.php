<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Internal Job Posting</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            line-height: 1.6;
            color: #333333;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header {
            background-color: #f75f00ff;
            /* A strong blue color */
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .content {
            padding: 30px;
        }

        .content h2 {
            color: #f94200ff;
            margin-top: 0;
            font-size: 20px;
        }

        .job-details {
            background-color: #f9f9f9;
            border-left: 4px solid #ff5a01ff;
            padding: 20px;
            margin: 20px 0;
        }

        .job-details ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .job-details li {
            padding: 8px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .job-details li:last-child {
            border-bottom: none;
        }

        .job-details strong {
            color: #555555;
            display: inline-block;
            width: 120px;
        }

        .cta-button {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 12px 20px;
            background-color: #ff4000ff;/ A nice,
            bright blue */ color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 20px 30px;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Exciting News! A New Job Has Been Posted</h1>
        </div>
        <div class="content">
            <h2>Hello,</h2>
            <p>We're excited to announce a new internal career opportunity. This could be your next step in growing with
                our company!</p>

            <div class="job-details">
                <h3>Job Details</h3>
                <ul>
                    <li><strong>Job Title:</strong> {{ ucfirst($job->job_title) }}</li>
                    <li><strong>Job Description:</strong> {{ ucfirst($job->job_description) }}</li>
                    <li><strong>Division:</strong> {{ ucfirst($job->division) }}</li>
                    <li><strong>Unit:</strong> {{ ucfirst($job->unit) }}</li>
                    <li><strong>Slots Available:</strong> {{ $job->slot_available }}</li>
                    <li><strong>Application Deadline:</strong>
                        {{ \Carbon\Carbon::parse($job->end_date)->format('F j, Y') }}</li>
                </ul>
            </div>

            <p>Ready to take on a new challenge?</p>
            <p>Rush to MyDMW Portal to Apply</p>
            {{-- <a href="[https://your-internal-job-portal.com/jobs/](https://your-internal-job-portal.com/jobs/){{ $job->id }}"
                class="cta-button">View & Apply Now</a> --}}

            <p style="margin-top: 30px;">Best regards,<br>The HR Team</p>
        </div>
        <div class="footer">
            <p>You are receiving this email because you are a valued employee of our company. Please do not reply to
                this email.</p>
        </div>
    </div>
</body>

</html>
