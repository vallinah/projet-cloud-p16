using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace AspNet.Models
{
    [Table("email_pins")]
    public class EmailPins
    {
        [Key]
        [Column("pin_id")]
        public int PinId { get; set; }

        [Column("code_pin")]
        [StringLength(250)]
        public string CodePin { get; set; }

        [Column("created_date")]
        [Required]
        public DateTime CreatedDate { get; set; }

        [Column("expiration_date")]
        [Required]
        public DateTime ExpirationDate { get; set; }

        [Column("user_id")]
        [Required]
        [StringLength(50)]
        public string UserId { get; set; }
    }
}