using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace AspNet.Models
{
    [Table("token")]
    public class Token
    {
        [Key]
        [Column("token_id")]
        public int PinId { get; set; }

        [Column("token")]
        [StringLength(250)]
        public string TokenCode { get; set; }

        [Column("created_date")]
        [Required]
        public DateTime CreatedDate { get; set; }

        [Column("user_id")]
        [Required]
        [StringLength(50)]
        public string UserId { get; set; }
    }
}