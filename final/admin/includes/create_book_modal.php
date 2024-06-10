<div id="createBookModal" class="modal" style="display:none;">
    <div class="modal-content" id="createBookModalContent">
        <button class="close" aria-label="Close" onclick="closeBookModal()" style="cursor:pointer;">&times;</button>
        <h2>Create Book</h2>
        <form action="books.php" method="POST" enctype="multipart/form-data" onsubmit="return validateCreateBookForm()">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                <div id="titleError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" required>
                <div id="authorError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="genre">Genre:</label>
                <select id="genre" name="genre" required>
                    <option value="">Select Genre</option>
                    <option value="Fiction">Fiction</option>
                    <option value="Mystery">Mystery</option>
                    <option value="Thriller">Thriller</option>
                    <option value="Non-Fiction">Non-Fiction</option>
                    <option value="Horror">Horror</option>
                    <option value="Fantasy">Fantasy</option>
                    <option value="Biography">Biography</option>
                    <option value="History">History</option>
                    <option value="Romance">Romance</option>
                    <option value="Poetry">Poetry</option>
                </select>
                <div id="genreError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
                <div id="descriptionError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="0" required>
                <div id="quantityError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" required>
                <div id="priceError" class="error-message"></div>
            </div>
            <div class="form-group">
                <label for="bookImage">Book Image:</label>
                <input type="file" id="bookImage" name="bookImage" required>
                <div id="bookImageError" class="error-message"></div>
            </div>
            <div class="form-actions">
                <button class="submit-button" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
function validateCreateBookForm() {
    const title = document.getElementById("title");
    const author = document.getElementById("author");
    const genre = document.getElementById("genre");
    const description = document.getElementById("description");
    const quantity = document.getElementById("quantity");
    const price = document.getElementById("price");
    const bookImage = document.getElementById("bookImage");

    document.querySelectorAll('.error-message').forEach(element => element.textContent = '');

    let isValid = true;

    if (title.value.trim() === '') {
        document.getElementById("titleError").textContent = "Title is required.";
        document.getElementById("titleError").style.color = 'red';
        isValid = false;
    }

    if (author.value.trim() === '') {
        document.getElementById("authorError").textContent = "Author is required.";
        document.getElementById("authorError").style.color = 'red';
        isValid = false;
    }

    if (genre.value === '') {
        document.getElementById("genreError").textContent = "Genre is required.";
        document.getElementById("genreError").style.color = 'red';
        isValid = false;
    }

    if (description.value.trim() === '') {
        document.getElementById("descriptionError").textContent = "Description is required.";
        document.getElementById("descriptionError").style.color = 'red';
        isValid = false;
    }

    if (quantity.value.trim() === '') {
        document.getElementById("quantityError").textContent = "Quantity is required.";
        document.getElementById("quantityError").style.color = 'red';
        isValid = false;
    }

    if (price.value.trim() === '') {
        document.getElementById("priceError").textContent = "Price is required.";
        document.getElementById("priceError").style.color = 'red';
        isValid = false;
    }

    if (isNaN(quantity.value) || isNaN(price.value)) {
        document.getElementById("quantityError").textContent = "Quantity must be a number.";
        document.getElementById("quantityError").style.color = 'red';
        document.getElementById("priceError").textContent = "Price must be a number.";
        document.getElementById("priceError").style.color = 'red';
        isValid = false;
    }

    if (bookImage.value.trim() === '') {
        document.getElementById("bookImageError").textContent = "Book Image is required.";
        document.getElementById("bookImageError").style.color = 'red';
        isValid = false;
    }

    return isValid;
}

function closeEditBookModal() {
    $('#editBookModal').hide();
}

function closeBookModal() {
    $('#createBookModal').hide();
}

$(document).mouseup(function(e) {
    var editModalContent = $("#editBookModalContent");
    if (!editModalContent.is(e.target) && editModalContent.has(e.target).length === 0) {
        closeEditBookModal();
    }

    var createModalContent = $("#createBookModalContent");
    if (!createModalContent.is(e.target) && createModalContent.has(e.target).length === 0) {
        closeBookModal();
    }
});

$(document).ready(function() {
    $('#editBookForm').on('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'books.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    alert(data.message);
                    $('#editBookModal').hide();
                    location.reload();
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Error editing book.');
            }
        });
    });

    $('#createBookForm').on('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'books.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    alert(data.message);
                    $('#createBookModal').hide();
                    location.reload();
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Error creating book.');
            }
        });
    });
});
</script>
