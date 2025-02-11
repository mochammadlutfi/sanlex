export function useBaseUtil() {
    /**
     * Mengkonversi object menjadi FormData
     * @param {Object} object - Object yang akan dikonversi
     * @returns {FormData}
     */
    const objectToFormData = (data) => {
        const formData = new FormData();
        for (const key in data) {
            const value = data[key];

            // Jika nilai adalah array
            if (Array.isArray(value)) {
                // Jika array berisi file
                if (key === 'images' && value.every(item => item instanceof File)) {
                    value.forEach((file, index) => {
                        formData.append(`${key}[${index}]`, file);
                    });
                } else {
                    // Jika array tidak berisi file, ubah menjadi string JSON
                    formData.append(key, JSON.stringify(value));
                }
            } else if (value instanceof File) {
                // Jika nilai adalah file
                formData.append(key, value);
            } else if (typeof value === 'object' && value !== null) {
                // Jika nilai adalah objek (non-array), ubah ke string JSON
                formData.append(key, JSON.stringify(value));
            } else if (value !== undefined) {
                // Tambahkan nilai primitif (string, number, boolean)
                formData.append(key, value);
            }
        }
        return formData;

    };

    return {
        objectToFormData,
    };
}
