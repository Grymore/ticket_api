@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://img.freepik.com/free-vector/black-mic-loudspeakers-vector-illustration-vintage-promotional-logo-concert-music-festival_74855-10591.jpg?w=1380&t=st=1669903285~exp=1669903885~hmac=48c4b0d9759b218b0df158d76880630deb479ab9f81b9a4051279b3a5a5a0309" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
