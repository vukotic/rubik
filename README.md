# Rubik's cube rotation

Laravel 9.24.0 and PHP 8.1.8

## Algorithm
- A cube is represented in 3D coordinate system, x, y, z
- The cube is divided in blocks (26 of them), each having its own set of coordinates
- The cube used is 3x3, therefore it's center is in (0,0,0) and all the coordinates of the blocks are some combination of -1, 0 and 1

### Rotation formula
- Each rotation is performed around one axis (x, y or z), therefore the dimension in the rotation axis is not changing during a rotation.
  This allows us to look at the rotation of blocks as two-dimensional in a plane perpendicular to the axis of rotation.

Euclidean geometry rules are being used, specifically the rotation of radius vector:

x' = x*cosA - y*sinA

y' = x*sinA + y*cosA

Where x' and y' are the new coordinates and A is the angle of rotation. Since we're always rotating by 90 degrees and sin90 = 1, cos90 = 0
the formula (for counterclockwise rotation) becomes:

x' = -y

y' = x

Or, in the case of clockwise rotation (sin-90 = -1):

x' = y

y' = -x

So, per example, if we're rotating a point (1,1,0) around Z axis:
- Z remains unchanged
- X becomes -1
- Y becomes 1

So the new coordinates for this block are (-1, 1, 0)

The block is represented by an object which, beside coordinates, keeps the information about the colors (Cx, Cy, Cz) representing the color of the blocks side perpendicular to the axis.
When a block is rotated, the colors of sides parallel to the axis of rotation switch places (if Z is the axis, Cx becomes Cy and vice-versa).

## Endpoints
There are three endpoints:

**cube/recreate** 

- GET, no parameters - resets the cube. 
- Returns json with cube parameters.

**cube/rotate/{axis}/{row}/{direction}** 

- GET, rotates the cube 
- {axis} can take values of x, y or z
- {row} can be 1, 2 or 3, representing which row are we rotating (e.g. bottom, middle or top)
- {direction} is the direction of the rotation, can be 'cw' (clockwise) or 'ccw' (counter-clockwise)
- returns json with cube parameters (an array of blocks).
- e.g. curl --request GET \
  --url http://127.0.0.1:8000/api/cube/rotate/z/3/ccw
- the curl above would rotate around the Z axis, third (top) row of the cube counterclockwise

**cube/display** 
- GET, no params. 
- This should show the cube in more readable format. _There might be some bugs or just vagueness with displaying the rotation around x and y axes, due to changing of the viewing perspective._ 

**cube/display/side/{side}** 
- GET, displays a side of the cube, part of the functionality above.
- The side can be F, B, U, D, L, R (front, back, up, down, left, right)


## Installation
- clone the repository
- go in the repo's directory, copy .env.example to .env and add your database parameters
- type the commands:
    - composer install
    - php artisan migrate
    - php artisan key:generate
    - php artisan serve




