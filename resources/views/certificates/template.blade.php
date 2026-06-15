<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; }
        .certificate-container {
            width: 100%;
            height: 100%;
            position: relative;
            text-align: center;
        }

        /* Modern Template */
        .modern { border: 10px solid #f3f4f6; padding: 40px; background-color: #ffffff; }
        .modern .header { color: #6b7280; text-transform: uppercase; letter-spacing: 2px; font-size: 14px; margin-bottom: 50px; }
        .modern .name { font-size: 40px; font-weight: bold; color: #111827; border-bottom: 2px solid #3b82f6; display: inline-block; padding-bottom: 10px; margin-bottom: 30px; }
        .modern .desc { font-size: 16px; color: #4b5563; line-height: 1.6; }
        .modern .event-title { font-weight: bold; color: #111827; font-size: 20px; }

        /* Classic Template */
        .classic { border: 8px double #d1d5db; padding: 50px; text-align: center; font-family: 'Times New Roman', serif; }
        .classic .header { font-size: 20px; letter-spacing: 5px; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #d1d5db; padding-bottom: 10px; margin-bottom: 40px; display: inline-block; }
        .classic .name { font-size: 45px; font-weight: bold; color: #111827; margin-bottom: 30px; }
        
        /* Accent Template */
        .accent { border: 2px solid #e5e7eb; position: relative; height: 100%; text-align: left; padding: 50px 50px 50px 100px; }
        .accent-sidebar { position: absolute; left: 0; top: 0; bottom: 0; width: 40px; background-color: #3b82f6; }
        .accent .header { color: #3b82f6; text-transform: uppercase; letter-spacing: 3px; font-weight: bold; margin-bottom: 30px;}
        .accent .name { font-size: 45px; font-weight: bold; border-left: 4px solid #3b82f6; padding-left: 20px; margin-bottom: 30px; }

        /* Centric Template */
        .centric { border: 4px solid #bfdbfe; padding: 60px; text-align: center; }
        .centric .seal { width: 80px; height: 80px; border: 2px dashed #3b82f6; border-radius: 50%; margin: 0 auto 30px; line-height: 80px; color: #3b82f6; font-weight: bold; }
        .centric .header { font-size: 30px; color: #3b82f6; margin-bottom: 10px; }
        .centric .name { font-size: 45px; font-family: 'Times New Roman', serif; margin-bottom: 20px; border-bottom: 1px solid #bfdbfe; display: inline-block; padding-bottom: 10px; }

        /* Common Footer */
        .footer { position: absolute; bottom: 50px; width: 100%; }
        .signature-line { width: 150px; border-bottom: 1px solid #9ca3af; margin: 0 auto 5px; }
        .signature-text { font-size: 10px; text-transform: uppercase; color: #6b7280; letter-spacing: 1px; }
        
        table.signatures { width: 100%; margin-top: 100px; text-align: center; }
        table.signatures td { width: 50%; }
    </style>
</head>
<body>

<div class="certificate-container {{ $template_style }}">
    @if($template_style == 'accent')
        <div class="accent-sidebar"></div>
    @endif

    @if($template_style == 'centric')
        <div class="seal">SEAL</div>
    @endif

    @if($template_style == 'classic')
        <div class="header">Certificate of Completion</div>
        <p style="font-style: italic; color: #4b5563;">This is to certify that</p>
    @elseif($template_style == 'modern')
        <div class="header">Certificate of Completion</div>
        <p style="color: #6b7280;">This is to certify that</p>
    @elseif($template_style == 'accent')
        <div class="header">Certificate</div>
        <p style="color: #6b7280;">Presented to</p>
    @elseif($template_style == 'centric')
        <div class="header">CERTIFICATE</div>
        <p style="text-transform: uppercase; letter-spacing: 2px; font-size: 12px; color: #6b7280; margin-bottom: 20px;">Of Achievement</p>
        <p style="font-style: italic; color: #9ca3af; font-size: 14px;">Proudly presented to</p>
    @endif

    <div class="name">{{ $participantName }}</div>

    <p class="desc">
        @if($template_style == 'accent')
            For successful completion of <br/>
        @elseif($template_style == 'centric')
            For demonstrating exceptional skill and completing <br/>
        @else
            has successfully completed all requirements for the <br/>
        @endif
        
        <span class="event-title">{{ $eventName }}</span> <br/><br/>
        on <span style="color: #6b7280;">{{ $eventDate }}</span>.
    </p>

    <table class="signatures">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div class="signature-text">Program Director</div>
            </td>
            @if($template_style == 'modern' || $template_style == 'classic')
            <td>
                <div class="signature-line"></div>
                <div class="signature-text">Lead Instructor</div>
            </td>
            @endif
        </tr>
    </table>
</div>

</body>
</html>
