type SloganProps = {
  className?: string;
};

export default function Slogan({ className = "" }: SloganProps) {
  return (
    <p className={`sofia-slogan ${className}`.trim()}>
      Tecnología que protege tu futuro
    </p>
  );
}
