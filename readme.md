# Appetiser Image Optimizer

**Contributors:** yourusername  
**Tags:** image optimization, webp, image compression, performance, media  
**Requires at least:** 5.0  
**Tested up to:** 6.4  
**Requires PHP:** 7.4  
**Stable tag:** 1.0.0  
**License:** GPL v3 
**License URI:** [GNU GPL v3](https://www.gnu.org/licenses/gpl-3.0.html)  

Optimizes newly uploaded images by reducing file size and automatically converting them to WebP format for improved performance and faster loading times.

## 📌 Description

Appetiser Image Optimizer automatically optimizes images upon upload, reducing file size while maintaining quality. It converts images to the **WebP format**, ensuring **faster load times** and **better performance** without affecting image quality.

### 🔹 Features
- ✅ **Automatic WebP conversion** for new uploads.
- ✅ **Lossless compression for different image sizes** – Reduces file size while preserving original quality.
- ✅ **Removes unused JPG files** after successful WebP conversion.
- ✅ **Works seamlessly with WordPress Media Library**.
- ✅ **Enable or disable optimization** from settings.
- ✅ **No impact on previously uploaded images**.

---

## 🚀 Installation

1. **Upload the plugin folder** to the `/wp-content/plugins/` directory.
2. **Activate the plugin** through the **"Plugins"** menu in WordPress.
3. **Go to** `Settings → Appetiser Image Optimizer` to enable or disable optimization.
4. **Upload new images**, and they will be optimized automatically.

---

## ❓ FAQ (Frequently Asked Questions)

### 🔹 Can I disable optimization?
Yes! You can **enable or disable image optimization** in the **plugin settings**.

### 🔹 Will this affect my existing images?
No, it **only optimizes newly uploaded images**. Existing images remain untouched.

### 🔹 Does this delete my original images?
The **original uploaded image remains**, but resized versions (thumbnails, medium, large) in JPG format are **deleted only if WebP was successfully created**.

### 🔹 What happens if WebP conversion fails?
If WebP conversion **fails**, the original image is **kept intact**, ensuring **no data loss**.

### 🔹 Do I need any special server setup?
Your server must support **Imagick** or **GD Library** with WebP support.

---

## 🖼️ Screenshots

1. **Settings Page** – Enable or disable image optimization.
2. **Media Uploads** – WebP conversion running on new images.

---

## 📜 Changelog

### 📌 1.0.0
- ✅ Initial release with **WebP optimization**.
- ✅ Automatic **JPG removal** for optimized images.
- ✅ Added **enable/disable** optimization settings.

---

## 🔄 Upgrade Notice

### 📌 1.0.0
- First stable release.

---

## 📜 License

This plugin is licensed under **GPL v3**. See [GNU GPL v3](https://www.gnu.org/licenses/gpl-3.0.html) for details.

This is an update to test triggers