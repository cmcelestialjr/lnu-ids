<div id="studentSearch">
    <label>Student</label>
    <select class="form-control select2-div studentSearch" id="discountStudentSelected" name="student">
    </select>
    <button class="btn btn-success btn-success-scan" id="discountAddStudent" name="add" style="width: 100%">
        <span class="fa fa-check"></span> Add Student
    </button>
</div><br>
<table class="table table-bordered">
    <thead>
        <th>ID No.</th>
        <th>Name</th>
        <th>Program</th>
        <th>Option</th>
    </thead>
    <tbody id="studentList">

    </tbody>
</table>
<script src="{{ asset('assets/js/search/student.js') }}"></script>