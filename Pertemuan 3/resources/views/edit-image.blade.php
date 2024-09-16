<!-- resources/views/edit-image.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        /* Target only the top-level canvas-container */
        .top-canvas-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
            background-color: #f8f9fa; /* Slight background to differentiate */
        }
        /* Ensure Fabric.js internal canvas-container is unaffected */
        .top-canvas-container > .canvas-container {
            border: none; /* Remove border styles */
            box-shadow: none; /* Remove box shadow styles */
        }
        canvas {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container main-container mt-4">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h2 class="card-title text-center mb-4">Edit Image</h2>
                <!-- Only the top-level div has the custom styles applied -->
                <div class="top-canvas-container w-100">
                    <canvas id="canvas"></canvas>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button onclick="toggleDrawingMode()" class="btn btn-outline-secondary">Toggle Drawing Mode</button>
                    <button onclick="rotate()" class="btn btn-outline-secondary">Rotate</button>
                    <button onclick="saveImage()" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Fabric.js canvas
        const canvas = new fabric.Canvas('canvas');

        // Load the uploaded image onto the canvas
        fabric.Image.fromURL('/uploads/{{ $filename }}', function(img) {
            img.set({ selectable: true });
            canvas.setWidth(document.querySelector('.top-canvas-container').clientWidth);
            canvas.setHeight(document.querySelector('.top-canvas-container').clientHeight);
            canvas.add(img);
            canvas.setActiveObject(img);
            canvas.sendToBack(img); // Ensure the image stays behind the drawings
        });

        // Adjust canvas size on window resize
        window.addEventListener('resize', () => {
            canvas.setWidth(document.querySelector('.top-canvas-container').clientWidth);
            canvas.setHeight(document.querySelector('.top-canvas-container').clientHeight);
            canvas.renderAll();
        });

        // Function to enable or disable free drawing mode
        function toggleDrawingMode() {
            canvas.isDrawingMode = !canvas.isDrawingMode;
            document.querySelector('button[onclick="toggleDrawingMode()"]').textContent = canvas.isDrawingMode ? 'Disable Drawing Mode' : 'Enable Drawing Mode';
        }

        // Customize drawing brush
        canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
        canvas.freeDrawingBrush.width = 3; // Adjust the width of the brush
        canvas.freeDrawingBrush.color = 'red'; // Adjust the color of the brush

        // Function to rotate the selected image
        function rotate() {
            const activeObject = canvas.getActiveObject();
            if (activeObject && activeObject.type === 'image') {
                activeObject.rotate(activeObject.angle + 90);
                canvas.renderAll();
            }
        }

        // Function to save the edited image
        function saveImage() {
            const dataURL = canvas.toDataURL({
                format: 'png',
                quality: 1
            });

            // Download or send the image to your backend server
            const link = document.createElement('a');
            link.href = dataURL;
            link.download = 'edited-image.png';
            link.click();
        }
    </script>
</body>
</html>
