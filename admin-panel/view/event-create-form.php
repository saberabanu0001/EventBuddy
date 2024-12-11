<!-- Event Title -->
<div class="mb-3">
  <label for="title" class="form-label">Title of the event</label>
  <input type="text" id="title" class="form-control w-100" name="title" placeholder="Enter the event title" required>
</div>

<!-- Event Date -->
<div class="mb-3">
  <label for="event_date" class="form-label">Event Date</label>
  <input type="date" id="event_date" class="form-control w-100" name="event_date" required>
</div>

<!-- Event Location -->
<div class="mb-3">
  <label for="location" class="form-label">Event Location</label>
  <input type="text" id="location" class="form-control w-100" name="location" placeholder="Enter the event location" required>
</div>

<!-- Event Details -->
<div class="mb-3">
  <label for="content" class="form-label">Event Details</label>
  <textarea id="content" class="form-control w-100" name="content" rows="3" placeholder="Provide event details" required></textarea>
</div>

<!-- Submit Button -->
<button type="submit" class="btn btn-primary w-100 mt-2">Create Event</button>