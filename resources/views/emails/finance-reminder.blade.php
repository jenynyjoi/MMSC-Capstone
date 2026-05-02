<x-mail::message>
# {{ $reminderType === 'overdue' ? 'Overdue Payment Notice' : ($reminderType === 'upcoming' ? 'Upcoming Payment Reminder' : 'Finance Account Reminder') }}

Dear Parent/Guardian of **{{ $studentName }}**,

@if($reminderType === 'overdue')
This is to inform you that **{{ $studentName }}** has an **overdue balance of ₱{{ $balance }}** for School Year {{ $schoolYear }}. Immediate settlement is required to avoid penalties.
@elseif($reminderType === 'upcoming')
This is a friendly reminder that **{{ $studentName }}** has an upcoming payment of **₱{{ $balance }}** due for School Year {{ $schoolYear }}. Please ensure timely payment.
@else
**{{ $studentName }}** has an outstanding balance of **₱{{ $balance }}** for School Year {{ $schoolYear }}. Please visit the Finance Office to settle the account.
@endif

<x-mail::panel>
**Student Name:** {{ $studentName }}
**School Year:** SY {{ $schoolYear }}
**Balance Due:** ₱{{ $balance }}
</x-mail::panel>

@if($note)
**Additional Note:** {{ $note }}

@endif

Please visit the Finance Office or contact us if you have any questions regarding your account.

Thank you,
**Finance Office**
My Messiah School of Cavite
</x-mail::message>
