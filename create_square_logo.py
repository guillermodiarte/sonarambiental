from PIL import Image

def create_square_logo(input_path, output_path):
    try:
        # Open the original image
        img = Image.open(input_path)
        original_width, original_height = img.size
        
        # Determine the size of the square (max dimension)
        max_dim = max(original_width, original_height)
        
        # Create a new white square image
        square_img = Image.new('RGB', (max_dim, max_dim), (255, 255, 255))
        
        # Calculate position to center the original image
        x_offset = (max_dim - original_width) // 2
        y_offset = (max_dim - original_height) // 2
        
        # Paste the original image onto the white square
        # Use the image itself as a mask if it has an alpha channel (transparency)
        if img.mode == 'RGBA':
            square_img.paste(img, (x_offset, y_offset), img)
        else:
            square_img.paste(img, (x_offset, y_offset))
            
        # Save the result
        square_img.save(output_path)
        print(f"Successfully created square logo at: {output_path}")
        
    except Exception as e:
        print(f"Error creating square logo: {e}")

if __name__ == "__main__":
    create_square_logo('img/logo_v3.png', 'img/logo_sq.png')
