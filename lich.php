<?php
// lich.php
include 'includes/header.php';
include 'includes/nav.php';
?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
<style>
    #calendar-container { max-width: 1100px; margin: 40px auto; padding: 0 10px; }
    #calendar { background-color: white; border-radius: 8px; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    :root { --fc-border-color: #ddd; --fc-daygrid-event-dot-width: 8px; --fc-today-bg-color: rgba(255, 222, 222, 0.75); --fc-button-bg-color: #d90429; --fc-button-active-bg-color: #a30000; --fc-button-hover-bg-color: #c40000; }
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 700px; border-radius: 8px; position: relative; }
    .close-btn { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close-btn:hover { color: black; }
    /* Form styling */
    #addEventForm .form-group { margin-bottom: 15px; }
    #addEventForm label { display: block; margin-bottom: 5px; font-weight: bold; }
    #addEventForm input, #addEventForm textarea, #addEventForm select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    #addEventForm input[type="color"] { padding: 5px; height: 40px; }
    #addEventForm .time-group { display: flex; gap: 10px; }
    #addEventForm button { background-color: #d90429; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
    /* View event styling */
    #viewEvent p { margin: 10px 0; font-size: 16px; }
    #viewEvent strong { color: #333; }
    #viewEvent .event-content { background-color: #f9f9f9; border-left: 4px solid #d90429; padding: 10px; margin-top: 10px; white-space: pre-wrap; }
    #download-link { background-color: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; }
</style>

<section>
    <h2 style="text-align: center; margin-top: 20px;">Lịch hoạt động và Sự kiện</h2>
    <div id='calendar-container'><div id='calendar'></div></div>
</section>

<!-- MODAL POPUP (HTML NÂNG CẤP) -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">×</span>
        <!-- View để hiển thị chi tiết sự kiện -->
        <div id="viewEvent">
            <h2 id="viewEventTitle" style="color:#d90429;"></h2>
            <p><strong>Thời gian:</strong> <span id="viewEventTime"></span></p>
            <p><strong>Tác giả:</strong> <span id="viewEventAuthor"></span></p>
            <p><strong>Nội dung:</strong></p>
            <div id="viewEventContent" class="event-content"></div>
            <p id="viewEventFile" style="margin-top: 15px;"><strong>Tệp đính kèm:</strong> <a id="download-link" href="#" target="_blank" download>Tải về</a></p>
        </div>
        <!-- Form để thêm sự kiện mới -->
        <form id="addEventForm" enctype="multipart/form-data">
            <h2>Thêm/Sửa sự kiện</h2>
            <div class="form-group time-group">
                <div style="flex:1;">
                    <label for="start_datetime">Thời gian bắt đầu:</label>
                    <input type="datetime-local" id="start_datetime" name="start_datetime" required>
                </div>
                <div style="flex:1;">
                    <label for="end_datetime">Thời gian kết thúc (tùy chọn):</label>
                    <input type="datetime-local" id="end_datetime" name="end_datetime">
                </div>
            </div>
            <div class="form-group"><label for="event_title">Tiêu đề sự kiện:</label><input type="text" id="event_title" name="title" required></div>
            <div class="form-group"><label for="event_author">Tác giả:</label><input type="text" id="event_author" name="author" required></div>
            <div class="form-group"><label for="event_content">Nội dung chi tiết:</label><textarea id="event_content" name="content" rows="3"></textarea></div>
            <div class="form-group"><label for="event_color">Màu sự kiện:</label><input type="color" id="event_color" name="color" value="#3788d8"></div>
            <div class="form-group"><label for="event_file">Tệp đính kèm (tùy chọn):</label><input type="file" id="event_file" name="event_file"></div>
            <button type="submit">Lưu sự kiện</button>
        </form>
    </div>
    
</div>
<div style="text-align: center; margin-top: 20px;">
            <a href="./index.php" style="display: inline-block; padding: 10px 20px; margin-bottom:50px; background: #ffc107; color: #000; border-radius: 5px; text-decoration: none; font-weight: 500;">
                ← Quay lại trang quản lý
            </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy các element của Modal
    const modal = document.getElementById('eventModal');
    const closeBtn = document.querySelector('.close-btn');
    const addEventForm = document.getElementById('addEventForm');
    const viewEventDiv = document.getElementById('viewEvent');

    // Mở/Đóng Modal
    const openModal = () => modal.style.display = 'block';
    const closeModal = () => modal.style.display = 'none';
    closeBtn.onclick = closeModal;
    window.onclick = (event) => { if (event.target == modal) closeModal(); };

    // Cấu hình FullCalendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listWeek' },
        events: 'api/get_events.php',
        selectable: true,
        editable: false, 

        dateClick: function(info) {
            addEventForm.reset(); // Xóa form
            
            document.getElementById('start_datetime').value = info.dateStr + 'T09:00';
            document.getElementById('end_datetime').value = info.dateStr + 'T10:00';
            document.getElementById('event_color').value = '#3788d8';

            viewEventDiv.style.display = 'none';
            addEventForm.style.display = 'block';
            openModal();
        },

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            
            // Format thời gian để hiển thị
            const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
            const startTime = new Date(info.event.start).toLocaleString('vi-VN', options);
            let timeString = startTime;
            if (info.event.end) {
                const endTime = new Date(info.event.end).toLocaleString('vi-VN', options);
                timeString += ' - ' + endTime;
            }

            // Đổ dữ liệu vào modal view
            document.getElementById('viewEventTitle').textContent = info.event.title;
            document.getElementById('viewEventTime').textContent = timeString;
            document.getElementById('viewEventAuthor').textContent = info.event.extendedProps.author;
            document.getElementById('viewEventContent').textContent = info.event.extendedProps.content || 'Không có.';
            
            const fileUrl = info.event.extendedProps.file_url;
            const fileView = document.getElementById('viewEventFile');
            if (fileUrl) {
                document.getElementById('download-link').href = fileUrl;
                fileView.style.display = 'block';
            } else {
                fileView.style.display = 'none';
            }
            
            addEventForm.style.display = 'none';
            viewEventDiv.style.display = 'block';
            openModal();
        }
    });
    calendar.render();

   
    addEventForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this); 

        fetch('api/add_events.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                calendar.refetchEvents();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

<?php
include 'includes/footer.php';
?>