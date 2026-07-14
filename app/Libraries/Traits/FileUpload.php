<?php

namespace App\Libraries\Traits;

use Illuminate\Http\Request;
use File;
use Image;

trait FileUpload
{
    /**
     * Process image uploading for both store() and update() method
     *
     * @param Request $request
     * @param string $request_for Decides request is coming from store() or update()
     * @param null $image_name Decides either update() request has existing image or not
     */
    public function uploadImage(Request $request, $request_for = 'store', $image_name = null)
    {


        $image = property_exists($this, 'image_request') && $this->image_request ? $this->image_request : $request->file('image');

        if ($image) {
            $this->image_name = time() . mt_rand(4100, 9999) . '_' . $image->getClientOriginalName();

            if (!file_exists($this->folder_path)) {
                File::makeDirectory($this->folder_path, 0775, true);
            }

            $image->move($this->folder_path, $this->image_name);


            /*if ($request_for == 'update') {
                if ($image_name) {

                    unlink($this->folder_path . DIRECTORY_SEPARATOR . $image_name);
                }
            }*/

        }

    }

    /**
     * Creates different size thumbs for uploaded image
     *
     * @param Request $request
     * @param string $request_for Defines what type of request
     * @param null $image_name will have value if request_for is update
     */
    public function uploadImageThumbs(Request $request, $request_for = 'store', $image_name = null)
    {

        //TODO: optimization still remained
        $image = property_exists($this, 'image_request') && $this->image_request ? $this->image_request : $request->file('image');


        //   dd($image);
        if ($image) {

            $image_dimensions = property_exists($this, 'use_image_dimensions') && $this->use_image_dimensions ? $this->gallery_image_dimensions : $this->image_dimensions;

            foreach ($image_dimensions as $image_dimension) {

                //open and resize image file
                $img = Image::make($this->folder_path . DIRECTORY_SEPARATOR . $this->image_name)->resize($image_dimension['width'], $image_dimension['height']);
                //save file as jpg in medium quality

              //  dd($this->folder_path . DIRECTORY_SEPARATOR . $image_dimension['width'] . '_' . $image_dimension['height'] . '_' . $this->image_name, 75);
                $img->save($this->folder_path . DIRECTORY_SEPARATOR . $image_dimension['width'] . '_' . $image_dimension['height'] . '_' . $this->image_name, 75);
            }
/*
            if ($request_for == 'update') {
                foreach ($this->image_dimensions as $image_dimension) {
                    if (file_exists($this->folder_path . DIRECTORY_SEPARATOR . $image_dimension['width'] . '_' . $image_dimension['height'] . '_' . $image_name)) {
                        unlink($this->folder_path . DIRECTORY_SEPARATOR . $image_dimension['width'] . '_' . $image_dimension['height'] . '_' . $image_name);
                    }
                }
            }*/

        }
    }

    /**
     * Creates different size thumbs for uploaded image
     *
     * @param Request $request
     * @param string $request_for Defines what type of request
     * @param null $image_name will have value if request_for is update
     */
    public function removeImage($image_name)
    {
        if ($image_name) {

            if (file_exists($this->folder_path . DIRECTORY_SEPARATOR . $image_name))

                unlink($this->folder_path . DIRECTORY_SEPARATOR . $image_name);

        }
    }

    public function removeImageThumbs($image_name, $dimensions = null)
    {
        if ($image_name) {

            $image_dimensions = $dimensions ? $dimensions : $this->image_dimensions;

            // remove old image thumb images
            if ($image_dimensions) {
                foreach ($image_dimensions as $image_dimension) {
                    if (file_exists($this->folder_path . DIRECTORY_SEPARATOR . $image_dimension['width'] . '_' . $image_dimension['height'] . '_' . $image_name)) {
                        unlink($this->folder_path . DIRECTORY_SEPARATOR . $image_dimension['width'] . '_' . $image_dimension['height'] . '_' . $image_name);
                    }
                }
            }

        }
    }


    public function createFolderIfNotExist()
    {
        if (!file_exists($this->folder_path)) {
            File::makeDirectory($this->folder_path, 0775, true);
        }
    }

}