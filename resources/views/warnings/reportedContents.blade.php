@extends('layouts.app')
@section('title', 'All of our Posts')
@section('content')
<button id="toggleButton">Toggle Content</button>

<div id="reportedPosts" class="hidden">


</div>

<div id="reportedComments" class="hidden">


</div>



<script>
    const toggleButton = document.getElementById('toggleButton');
    const reportedPosts = document.getElementById('reportedPosts');
    const reportedComments = document.getElementById('reportedComments');

    toggleButton.addEventListener('click', function() {
        if (reportedPosts.classList.contains('hidden')) {
            // Show reported posts and hide reported comments
            reportedPosts.classList.remove('hidden');
            reportedComments.classList.add('hidden');
        } else {
            // Show reported comments and hide reported posts
            reportedPosts.classList.add('hidden');
            reportedComments.classList.remove('hidden');
        }
    });
</script>

</body>
</html>
