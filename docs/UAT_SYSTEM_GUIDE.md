# UAT System Guide

## Overview
The UAT (User Acceptance Testing) system allows you to create test projects, add test cases, invite users (both internal employees and external clients), and collect feedback efficiently.

## Features

### For Internal Users (Employees)
- ✅ Create and manage UAT projects
- ✅ Add/edit/delete test cases
- ✅ Add both internal and external users to projects
- ✅ View all feedback from all users
- ✅ Copy and share UAT links
- ✅ Set project deadlines and statuses
- ✅ Track test case priorities (Low, Medium, High, Critical)

### For External Users (Clients)
- ✅ Access UAT projects via secure link
- ✅ View all test cases and instructions
- ✅ Mark test cases with status (Pending, Passed, Failed, Blocked)
- ✅ Leave detailed feedback and comments
- ✅ Upload attachments (screenshots, documents)
- ✅ Quick status updates with one click

## How to Use

### Step 1: Create a UAT Project
1. Navigate to **UAT** in the main menu
2. Click **"Create UAT Project"**
3. Fill in:
   - **Project Name** (required)
   - **Description** (optional)
   - **Deadline** (optional)
   - **Users** (required - add at least one user)
     - Name
     - Email
     - Role: Internal (Employee) or External (Client)
4. Click **"Create Project"**

### Step 2: Add Test Cases
1. Open your UAT project
2. Click **"Add Test Case"** 
3. Fill in the modern interactive form:
   - **Title** (required) - Clear test case name
   - **Priority** (Low/Medium/High/Critical) - Color-coded with emojis
   - **Description** (optional) - Brief overview
   - **Testing Steps** (interactive) - Add multiple steps dynamically:
     - Click **"Add Step"** to add more steps
     - Each step gets an auto-numbered badge
     - Remove steps by hovering and clicking the X button
     - Steps are automatically formatted with numbers
   - **Expected Result** (optional but recommended)
4. Click **"Create Test Case"**

**New Features:**
- 📝 **Step-by-Step Input**: Add individual steps with dedicated input fields
- 🎨 **Modern Card Design**: Beautiful gradient background with clear sections
- 🔢 **Auto-Numbering**: Steps are automatically numbered
- ➕ **Dynamic Steps**: Add/remove steps as needed
- 🎯 **Visual Hierarchy**: Color-coded icons for each section
- ✨ **Smooth Animations**: Professional transitions and hover effects

### Step 3: Share UAT Link
1. On the project page, click **"Copy UAT Link"**
2. Send this link to your users via email or other communication channels
3. Users will need to authenticate with their registered email address

### Step 4: Users Provide Feedback
**External Users can:**
- Click quick status buttons (Passed/Failed/Blocked)
- Add detailed comments and feedback
- Upload screenshots or documents
- See their own feedback status

**Internal Users can also:**
- Create and manage test cases
- Add more users to the project
- View feedback from all users

## User Roles

### Internal (Employee)
- Full access to create and edit test cases
- Can add new users (both internal and external)
- Can view all feedback from all users
- Perfect for: QA team, developers, project managers

### External (Client)
- Can only view and test existing test cases
- Can provide feedback and change status
- Cannot create new test cases
- Cannot add users
- Perfect for: clients, stakeholders, beta testers

## Test Case Priorities

- 🔴 **Critical**: Must be tested and fixed immediately
- 🟠 **High**: Important features that should be tested soon
- 🟡 **Medium**: Standard features (default)
- ⚪ **Low**: Nice-to-have features, can be tested later

## Feedback Statuses

- ⚪ **Pending**: Not yet tested (default)
- ✅ **Passed**: Test passed successfully
- ❌ **Failed**: Test failed, issues found
- 🚫 **Blocked**: Cannot test due to blockers

## Tips for Success

1. **Write Clear Test Cases**: Include detailed steps and expected results
2. **Set Priorities**: Help testers focus on critical features first
3. **Add Deadlines**: Keep projects on track
4. **Regular Updates**: Check feedback regularly and respond
5. **Use Attachments**: Screenshots help communicate issues better
6. **Internal Users**: Leverage the ability to see all feedback for better coordination

## Technical Details

### Database Tables
- `uat_projects` - Stores UAT projects
- `uat_users` - Stores project users and their roles
- `uat_test_cases` - Stores test cases
- `uat_feedbacks` - Stores user feedback and status

### Routes
- **Authenticated Routes** (for employees):
  - `/uat` - List all projects
  - `/uat/create` - Create new project
  - `/uat/{project}` - View project details
  - `/uat/{project}/edit` - Edit project
  
- **Public Routes** (no login required):
  - `/uat/{token}` - Public UAT view (requires email authentication)

### Security
- Public links use unique tokens (32 characters)
- Email-based authentication for accessing UAT projects
- Session-based user tracking
- Only authorized users (added to project) can access

## Support

For any issues or questions about the UAT system, please contact your system administrator.

---

**Note**: The UAT system uses your brand colors (black/white) and maintains consistency with your existing UI/UX design patterns.

