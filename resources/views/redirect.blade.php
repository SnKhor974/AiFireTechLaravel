<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
    <form id="redirectForm" action="{{ route('admin-view-user') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="search" value="id">
        <input type="hidden" name="search_id" value="{{ $fe_user_id }}">
    </form>
    <script type="text/javascript">
        document.getElementById('redirectForm').submit();
    </script>
</body>
</html>