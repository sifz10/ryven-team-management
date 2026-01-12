# Chatbot File & Voice Recording Features

## ✨ New Features Added

### 1. **File Upload**
- 📎 Click file icon in input toolbar to attach files
- Supports: Images, PDFs, Documents (DOC/DOCX), Excel sheets, PowerPoints, TXT, ZIP
- Max file size: 20MB per file
- Multiple files can be attached at once
- Preview chips show before sending
- Remove individual files by clicking X button

### 2. **Voice Recording**
- 🎤 Click microphone icon to start/stop recording
- Records audio in WebM format for broad browser compatibility
- Real-time indicator: Button turns red while recording
- Recorded audio appears as attachment chip
- Playable audio player when displayed in chat

### 3. **Enhanced Message Display**
- ✅ Text messages display as before
- 🖼️ Images: Clickable thumbnails (max-height: 192px)
- 🎵 Audio: Embedded HTML5 player with controls
- 📄 Documents: Downloadable file links with file icons
- All types can be mixed in a single message

## 🛠️ Technical Implementation

### Frontend (JavaScript)
```javascript
// File Management
- fileInput: Hidden file input with accept filters
- selectedFiles: Array tracking selected/recorded files
- updateAttachmentsPreview(): Visual feedback for attachments
- removeFile(): Individual file removal
- Drag-free interface with chips
```

### Voice Recording
```javascript
// MediaRecorder API
- navigator.mediaDevices.getUserMedia({ audio: true })
- Blob conversion to WebM format
- Auto-adds to selectedFiles on stop
- Visual feedback: Button color change during recording
```

### Send Mechanism
```javascript
// FormData Upload
- Changed from JSON to FormData for file support
- Attachments array sent as multipart/form-data
- Files stored in storage/public/chatbot-attachments/{conversationId}/
```

### Backend (Laravel)
**Updated Controller**: `app/Http/Controllers/Admin/ChatbotController.php`
- `sendReply()`: Now accepts files array, stores them, creates DB records
- `getMessages()`: Returns attachment data with URLs for display

**New Migration**: `2025_01_12_create_chat_message_attachments_table`
- Stores: file_path, file_name, file_type, file_size
- Links to chat_messages via foreign key
- Auto-deletes with message

### Database Table
```sql
chat_message_attachments
├── id (primary key)
├── chat_message_id (foreign key)
├── file_path (relative path in storage)
├── file_name (original filename)
├── file_type (MIME type)
├── file_size (bytes)
├── created_at / updated_at
└── index on chat_message_id
```

## 🎨 UI/UX Enhancements

### Input Area
- Toolbar with two icon buttons (files & voice)
- Gray background, red highlight during recording
- Attachment preview chips with remove buttons
- Existing message input + send button unchanged

### Message Display
- Attachments grouped in flex container
- Images: Rounded corners, hover effect, click to view full size
- Audio: Styled player container with dark background
- Files: Link-style chips with file icon and name
- Smooth animations on all elements

### Responsive
- Buttons collapse to icons on mobile
- Preview chips wrap to next line
- Audio player responsive width
- Works on all screen sizes

## 📊 File Support

**Accepted Types**:
- Images: `image/*` (JPEG, PNG, GIF, WebP, etc.)
- Audio: `audio/*` (WebM from recorder, also MP3, WAV)
- Documents: `.doc, .docx, .txt, .pdf, .xls, .xlsx, .ppt, .pptx, .zip`

**Size Limits**:
- Per file: 20MB
- No total conversation limit (can add more files later)
- Validated on both client & server

## 🔄 Real-Time Integration

- Attachments included in real-time broadcasts
- Polling includes full attachment data
- Both admin and widget can receive/display files
- Audio files playable immediately after message arrives

## 📱 Browser Compatibility

- **File Upload**: All modern browsers (Chrome, Firefox, Safari, Edge)
- **Voice Recording**: Chrome, Firefox, Edge (Safari partial support)
  - Fallback: File upload alternative always available
  - Error handling: User-friendly messages

## 🔒 Security

- File type validation (MIME & extension)
- File size limits enforced server-side
- Files stored in dedicated directory with conversation isolation
- Access via Laravel storage (not directly exposed)
- CSRF token required for uploads

## 🚀 Future Enhancements

Potential additions:
- Drag & drop file upload
- Image compression before upload
- Audio transcription (AI)
- File preview before download
- Attachment download counter
- Share attachments with team

## 📝 Testing Checklist

- [ ] Upload single file - verify storage & display
- [ ] Upload multiple files together
- [ ] Record voice message
- [ ] Display audio player in chat
- [ ] Send message without text, with files only
- [ ] Send message with text + files
- [ ] Verify real-time delivery of attachments
- [ ] Test polling fallback with attachments
- [ ] Mobile: Icon buttons visible & functional
- [ ] Dark mode: Attachment styling correct
- [ ] Remove file from preview - works
- [ ] Record > cancel (stop) - works
